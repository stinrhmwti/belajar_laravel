<?php

namespace App\Http\Controllers;

use App\Models\HasilUjian;
use App\Models\KategoriUjian;
use App\Models\Mapel;
use App\Models\Soal;
use App\Models\User;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function dashboard()
    {
        $total_soal = Soal::count();
        $total_murid = User::where('role', 'murid')->count();
        $total_ujian = HasilUjian::count();
        $total_mapel = Mapel::count();
        $murid_aktif = HasilUjian::distinct('user_id')->count('user_id');
        $rata_semua = round(HasilUjian::avg('nilai') ?? 0, 2);
        $soal_per_mapel = Mapel::withCount('soals')->get();
        $ujian_terbaru = HasilUjian::with(['murid', 'mapel'])->orderByDesc('id')->take(5)->get();

        return view('guru.dashboard', compact('total_soal', 'total_murid', 'total_ujian', 'total_mapel', 'murid_aktif', 'rata_semua', 'soal_per_mapel', 'ujian_terbaru'));
    }

    public function soal()
    {
        $mapels = Mapel::all();
        $soals = Soal::with('mapel')->get();

        return view('guru.soal', compact('soals', 'mapels'));
    }

    public function tambahSoal()
    {
        $mapels = Mapel::all();
        $kategoris = KategoriUjian::all();

        return view('guru.tambah_soal', compact('mapels', 'kategoris'));
    }

    public function simpanSoal(Request $request)
    {
        Soal::create($request->all());

        return redirect()->route('guru.soal')->with('success', 'Soal berhasil ditambahkan!');
    }

    public function editSoal($id)
    {
        $soal = Soal::findOrFail($id);
        $mapels = Mapel::all();
        $kategoris = KategoriUjian::all();

        return view('guru.edit_soal', compact('soal', 'mapels', 'kategoris'));
    }

    public function updateSoal(Request $request, $id)
    {
        Soal::findOrFail($id)->update($request->all());

        return redirect()->route('guru.soal')->with('success', 'Soal berhasil diupdate!');
    }

    public function hapusSoal($id)
    {
        Soal::findOrFail($id)->delete();

        return redirect()->route('guru.soal')->with('success', 'Soal berhasil dihapus!');
    }

    public function lihatNilai()
    {
        $hasil = HasilUjian::with(['murid', 'mapel', 'kategori'])->orderByDesc('id')->get();

        return view('guru.nilai', compact('hasil'));
    }

    public function raport()
    {
        $murids = User::where('role', 'murid')->get();
        $mapels = Mapel::all();

        return view('guru.raport', compact('murids','mapels'));
    }
}
