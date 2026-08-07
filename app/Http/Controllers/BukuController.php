<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BukuController extends Controller
{
    // Menampilkan daftar buku
    public function index()
    {
        $dataBuku = Buku::latest()->get();

        return view('buku.index', compact('dataBuku'));
    }

    // Menampilkan form tambah buku
    public function create()
    {
        return view('buku.tambah');
    }

    // Menyimpan data buku baru
    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'sampul' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:10240',
        ]);

        $data = $request->only(['judul', 'penulis']);

        if ($request->hasFile('sampul')) {
            $data['sampul'] = $request->file('sampul')->store('sampul', 'public');
        }

        Buku::create($data);

        return redirect('/buku')->with('success', 'Buku berhasil ditambahkan!');
    }

    // Menghapus data buku
    public function destroy($id)
    {
        $buku = Buku::findOrFail($id);

        if ($buku->sampul) {
            Storage::disk('public')->delete($buku->sampul);
        }

        $buku->delete();

        return redirect('/buku')->with('success', 'Buku berhasil dihapus!');
    }
}
