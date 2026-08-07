<?php

namespace App\Http\Controllers;

use App\Models\HasilUjian;
use App\Models\KategoriUjian;
use App\Models\Mapel;
use App\Models\Soal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MuridController extends Controller
{
    public function dashboard()
    {
        $hasil = HasilUjian::with(['mapel', 'kategori'])
            ->where('user_id', Auth::id())
            ->orderByDesc('id')
            ->get();
        $total_ujian = $hasil->count();
        $rata_nilai = round($hasil->avg('nilai') ?? 0);
        $total_benar = $hasil->sum('jumlah_benar');
        $total_salah = $hasil->sum('jumlah_salah');
        $total_mapel = Mapel::count();
        $mapel_diikuti = HasilUjian::where('user_id', Auth::id())
            ->distinct('mapel_id')->count('mapel_id');

        return view('murid.dashboard', compact(
            'hasil', 'total_ujian', 'rata_nilai',
            'total_benar', 'total_salah', 'total_mapel', 'mapel_diikuti'
        ));
    }

    public function pilihMapel()
    {
        $mapels = Mapel::all();

        return view('murid.pilih_mapel', compact('mapels'));
    }

    public function pilihKategori($mapel_id)
    {
        $mapel = Mapel::findOrFail($mapel_id);
        // Ambil kategori yang ada soalnya untuk mapel ini
        $kategori_ids = Soal::where('mapel_id', $mapel_id)
            ->distinct('kategori_id')
            ->pluck('kategori_id');
        $kategoris = KategoriUjian::whereIn('id', $kategori_ids)->get();

        return view('murid.pilih_kategori', compact('mapel', 'kategoris'));
    }

    public function mulaiUjian($mapel_id, $kategori_id)
    {
        $mapel = Mapel::findOrFail($mapel_id);
        $kategori = KategoriUjian::findOrFail($kategori_id);
        $soals = Soal::where('mapel_id', $mapel_id)
            ->where('kategori_id', $kategori_id)
            ->get();
        if ($soals->isEmpty()) {
            return redirect()->route('murid.pilih-kategori', $mapel_id)
                ->with('error', 'Tidak ada soal untuk kategori ini');
        }

        return view('murid.ujian', compact('soals', 'mapel', 'kategori'));
    }

    public function kumpulkanUjian(Request $request, $mapel_id, $kategori_id)
    {
        $soals = Soal::where('mapel_id', $mapel_id)
            ->where('kategori_id', $kategori_id)
            ->get();

        $benar = 0;
        foreach ($soals as $soal) {
            $jawaban = $request->input('jawaban_'.$soal->id);
            $kunci = $soal->jawaban_benar ?? $soal->kunci_jawaban;
            if ($jawaban !== null && strtoupper($jawaban) == strtoupper($kunci)) {
                $benar++;
            }
        }

        $salah = $soals->count() - $benar;
        $nilai = $soals->count() > 0
            ? round(($benar / $soals->count()) * 100)
            : 0;

        $hasil = HasilUjian::create([
            'user_id' => Auth::id(),
            'mapel_id' => $mapel_id,
            'kategori_id' => $kategori_id,
            'jumlah_benar' => $benar,
            'jumlah_salah' => $salah,
            'nilai' => $nilai,
            'tanggal' => now()->format('Y-m-d H:i:s'),
        ]);

        return redirect()->route('murid.hasil', $hasil->id)
            ->with('success', "Ujian selesai! Nilai kamu: $nilai");
    }

    public function raport()
    {
        $hasil = HasilUjian::with(['mapel', 'kategori'])
            ->where('user_id', Auth::id())
            ->get();

        return view('murid.raport', compact('hasil'));
    }

    public function hasil($id)
    {
        $hasil = HasilUjian::with(['mapel', 'kategori'])
            ->findOrFail($id);

        return view('murid.hasil', compact('hasil'));
    }
}
