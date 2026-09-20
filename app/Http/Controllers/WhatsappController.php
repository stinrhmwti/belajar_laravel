<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendWhatsappRequest;
use App\Jobs\KirimPesanWhatsApp;
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

        // Filter berdasarkan status pengiriman (pending, sent, failed)
        if ($request->filled('status')) {
            $status = $request->status;
            if ($status === 'success' || $status === 'sent') {
                $query->whereIn('status', [WhatsappLog::STATUS_SENT, WhatsappLog::STATUS_SUCCESS]);
            } else {
                $query->where('status', $status);
            }
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
     * Mendaftarkan pesan WhatsApp teks manual atau berbasis template ke dalam Antrean (Queue).
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
                'success' => ! $log->isFailed(),
                'message' => $log->isFailed()
                    ? ($log->error_message ?: 'Gagal memproses pengiriman WhatsApp.')
                    : 'Pesan masuk antrean dan akan dikirim.',
                'data' => $log->load(['template', 'user']),
            ], $log->isFailed() ? 422 : 202);
        }

        // Jika nomor tidak valid sejak awal (error format langsung failed)
        if ($log->isFailed()) {
            return back()
                ->with('error', 'Gagal memproses pesan: ' . ($log->error_message ?: 'Nomor WhatsApp tidak valid.'))
                ->with('direct_wa_url', $log->wa_url)
                ->with('failed_phone', $log->phone);
        }

        // Respon redirect web dengan notifikasi antrean
        return back()->with('success', 'Pesan masuk antrean dan akan dikirim ke nomor ' . $log->phone);
    }

    /**
     * Kirim ulang pesan yang gagal secara manual dengan memasukkannya kembali ke Antrean (Queue).
     */
    public function resend(WhatsappLog $log): JsonResponse|RedirectResponse
    {
        // 1. Reset status menjadi pending, attempts menjadi 0, dan kosongkan error_message lama
        $log->update([
            'status' => WhatsappLog::STATUS_PENDING,
            'attempts' => 0,
            'error_message' => null,
        ]);

        // 2. Dispatch kembali Job ke Antrean (Queue) setelah transaksi database selesai
        KirimPesanWhatsApp::dispatch($log->id)->afterCommit();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Pesan berhasil dimasukkan kembali ke antrean untuk dikirim ulang.',
                'data' => $log->load(['template', 'user']),
            ], 200);
        }

        return back()->with('success', 'Pesan berhasil dimasukkan kembali ke antrean untuk dikirim ulang ke nomor ' . $log->phone);
    }

    /**
     * Tampilkan detail data log WhatsApp dalam format JSON.
     */
    public function show(WhatsappLog $log): JsonResponse
    {
        return response()->json($log->load(['template', 'user']));
    }
}
