<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendWhatsappRequest;
use App\Models\WhatsappLog;
use App\Models\WhatsappTemplate;
use App\Services\WhatsappService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WhatsappController extends Controller
{
    protected WhatsappService $whatsapp;

    /**
     * Dependency injection WhatsappService melalui constructor.
     */
    public function __construct(WhatsappService $whatsapp)
    {
        $this->whatsapp = $whatsapp;
    }

    /**
     * Tampilkan riwayat pengiriman pesan WhatsApp beserta filter dan form kirim.
     */
    public function index(Request $request): View
    {
        $query = WhatsappLog::with(['template', 'user'])->latest();

        // Filter berdasarkan status pengiriman (pending, success, failed)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter pencarian berdasarkan nomor telepon, isi pesan, atau nama user
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('phone', 'like', "%{$search}%")
                    ->orWhere('message', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $logs = $query->paginate(20)->withQueryString();
        $templates = WhatsappTemplate::active()->orderBy('name')->get();
        $users = \App\Models\User::orderBy('name')->get();
        $vehicles = \App\Models\Vehicle::orderBy('plat_nomor')->get();

        return view('whatsapp.index', compact('logs', 'templates', 'users', 'vehicles'));
    }

    /**
     * Mengirim pesan WhatsApp teks manual atau berbasis template.
     */
    public function send(SendWhatsappRequest $request): JsonResponse|RedirectResponse
    {
        $phone = (string) $request->phone;
        $meta = [
            'user_id' => $request->user_id,
        ];

        // Jika template_code diisi, gunakan sendTemplate()
        if ($request->filled('template_code')) {
            $data = $request->input('data', []);
            $log = $this->whatsapp->sendTemplate($request->template_code, $phone, $data, $meta);
        } else {
            // Jika tidak, gunakan pesan teks manual send()
            $log = $this->whatsapp->send($phone, (string) $request->message, $meta);
        }

        // Respon JSON jika diminta (API request)
        if ($request->expectsJson()) {
            return response()->json([
                'success' => $log->isSuccess(),
                'message' => $log->isSuccess()
                    ? 'Pesan WhatsApp berhasil dikirim.'
                    : ($log->error_message ?: 'Gagal mengirim pesan WhatsApp.'),
                'data' => $log->load(['template', 'user']),
            ], $log->isSuccess() ? 200 : 422);
        }

        // Respon redirect dengan flash session untuk request Blade Web
        if ($log->isSuccess()) {
            return back()->with('success', 'Pesan WhatsApp berhasil dikirim ke nomor ' . $log->phone);
        }

        return back()
            ->with('error', 'Gagal mengirim pesan WhatsApp ke ' . $log->phone . ': ' . ($log->error_message ?: 'Terjadi kendala pada layanan.'))
            ->with('direct_wa_url', $log->wa_url)
            ->with('failed_phone', $log->phone);
    }

    /**
     * Kirim ulang pesan yang gagal dengan isi log yang sama.
     */
    public function resend(WhatsappLog $log): JsonResponse|RedirectResponse
    {
        $newLog = $this->whatsapp->send(
            $log->phone,
            $log->message,
            [
                'template_id' => $log->whatsapp_template_id,
                'user_id' => $log->user_id,
            ]
        );

        if (request()->expectsJson()) {
            return response()->json([
                'success' => $newLog->isSuccess(),
                'message' => $newLog->isSuccess()
                    ? 'Pesan WhatsApp berhasil dikirim ulang.'
                    : ($newLog->error_message ?: 'Gagal mengirim ulang pesan WhatsApp.'),
                'data' => $newLog->load(['template', 'user']),
            ], $newLog->isSuccess() ? 200 : 422);
        }

        if ($newLog->isSuccess()) {
            return back()->with('success', 'Pesan WhatsApp berhasil dikirim ulang ke nomor ' . $newLog->phone);
        }

        return back()
            ->with('error', 'Gagal mengirim ulang pesan ke ' . $newLog->phone . ': ' . ($newLog->error_message ?: 'Terjadi kesalahan sistem.'))
            ->with('direct_wa_url', $newLog->wa_url)
            ->with('failed_phone', $newLog->phone);
    }


    /**
     * Tampilkan detail data log WhatsApp dalam format JSON.
     */
    public function show(WhatsappLog $log): JsonResponse
    {
        return response()->json($log->load(['template', 'user']));
    }
}
