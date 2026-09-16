<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Tampilkan daftar pengguna sistem armada (admin, teknisi, user).
     * Data guru/murid dari sistem sekolah lama sengaja tidak ditampilkan di sini
     * supaya tidak tercampur.
     */
    public function index()
    {
        $users = User::whereIn('role', ['superadmin', 'admin', 'teknisi', 'user', 'pimpinan'])
            ->orderBy('name')
            ->get();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:superadmin,admin,teknisi,user,pimpinan',
            'no_telepon' => 'nullable|string|max:30',
            'nomor_sim' => 'nullable|string|max:50',
            'jenis_sim' => 'nullable|string|in:SIM A,SIM B1,SIM B2,SIM C,Lainnya',
            'masa_berlaku_sim' => 'nullable|date',
            'nis' => 'nullable|string|max:50',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        // Kolom kelas milik sistem sekolah lama, tidak dipakai untuk
        // akun sistem armada
        $validated['kelas'] = null;

        User::create($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,'.$user->id,
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'role' => 'required|in:superadmin,admin,teknisi,user,pimpinan',
            'password' => 'nullable|string|min:6',
            'no_telepon' => 'nullable|string|max:30',
            'nomor_sim' => 'nullable|string|max:50',
            'jenis_sim' => 'nullable|string|in:SIM A,SIM B1,SIM B2,SIM C,Lainnya',
            'masa_berlaku_sim' => 'nullable|date',
            'nis' => 'nullable|string|max:50',
        ]);

        if (! empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }

    public function updateProfile(Request $request)
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            'no_telepon' => 'nullable|string|max:30',
            'nomor_sim' => 'nullable|string|max:50',
            'jenis_sim' => 'nullable|string|in:SIM A,SIM B1,SIM B2,SIM C,Lainnya',
            'masa_berlaku_sim' => 'nullable|date',
            'nis' => 'nullable|string|max:50',
            'password' => 'nullable|string|min:6|confirmed',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        if (array_key_exists('no_telepon', $validated)) {
            $user->no_telepon = $validated['no_telepon'];
        }
        if (array_key_exists('nomor_sim', $validated)) {
            $user->nomor_sim = $validated['nomor_sim'];
        }
        if (array_key_exists('jenis_sim', $validated)) {
            $user->jenis_sim = $validated['jenis_sim'];
        }
        if (array_key_exists('masa_berlaku_sim', $validated)) {
            $user->masa_berlaku_sim = $validated['masa_berlaku_sim'];
        }
        $user->nis = $validated['nis'] ?? $user->nis;

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time().'_'.$user->id.'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/avatars'), $filename);
            $user->kelas = 'uploads/avatars/'.$filename; // Store in kelas column
        }

        $user->save();

        return back()->with('success', 'Profil Anda berhasil diperbarui!');
    }
}
