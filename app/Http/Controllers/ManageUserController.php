<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use App\Models\Tim;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ManageUserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->get();
        $roles = Role::all();
        $timList = Tim::orderBy('nama_tim')->get();
        return view('manage.user.index', compact('users', 'roles', 'timList'));
    }

    public function create()
    {
        $roles = Role::all();
        $jabatans = Pegawai::select('jabatan')->distinct()->orderBy('jabatan')->pluck('jabatan');
        $golongans = Pegawai::select('golongan_akhir')->distinct()->orderBy('golongan_akhir')->pluck('golongan_akhir');
        return view('manage.user.create', compact('roles', 'jabatans', 'golongans'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'            => 'required|string|max:255',
            'nip_lama'        => 'required|string|max:255|unique:users,nip_lama',
            'nip_baru'        => 'required|string|max:255|unique:pegawai,nip_baru',
            'jabatan'         => 'required',
            'golongan_akhir'  => 'required',
            'tamat_gol'  => 'required|date',
            'pendidikan'      => 'required|string|max:255',
            'tanggal_lulus'   => 'required|date',
            'jenis_kelamin' => 'required|in:LK,PR',
            'username'        => 'required|string|max:255|unique:users,username',
            'password'        => 'required|string|min:6',
            'email'           => 'required|string|email|max:255|unique:users,email',
            'id_role'         => 'required|exists:role,id',
        ]);

        Pegawai::create([
            'nama'            => $request->nama,
            'nip_lama'        => $request->nip_lama,
            'nip_baru'        => $request->nip_baru,
            'jabatan'         => $request->jabatan,
            'golongan_akhir'  => $request->golongan_akhir,
            'tamat_gol'       => $request->tamat_gol,
            'pendidikan'      => $request->pendidikan,
            'tanggal_lulus'   => $request->tanggal_lulus,
            'jenis_kelamin'   => $request->jenis_kelamin,
            'email'   => $request->email
        ]);

        // Default tim_id null
        $timId = null;
        if ($request->id_role == 3) {
            $timId = 9;
        }

        User::create([
            'nip_lama' => $request->nip_lama,
            'username' => $request->username,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'id_role'  => $request->id_role,
            'tim_id'   => $timId
        ]);

        return redirect()->route('manage.user.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all();
        return view('manage.user.edit', compact('user', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email'    => 'required|string|email|max:255|unique:users,email,' . $id,
            'id_role'  => 'required|exists:role,id',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:6';
        }

        $request->validate($rules);

        $userData = [
            'username' => $request->username,
            'email'    => $request->email,
            'id_role'  => $request->id_role,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        if ($request->id_role == 3) {
            $userData['tim_id'] = 9; // Admin
        } elseif ($request->id_role == 1) {
            $userData['tim_id'] = null; // Operator
        } elseif ($request->id_role == 2) {
            $userData['tim_id'] = $user->tim_id;
        }

        $user->update($userData);

        return redirect()->route('manage.user.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function updateRoleUser(Request $request, $id)
    {
        $request->validate([
            'id_role' => 'required|exists:role,id',
        ]);

        $user = User::findOrFail($id);

        $user->id_role = $request->id_role;

        if ($request->id_role == 3) {
            $user->tim_id = 9; // Admin
        } elseif ($request->id_role == 1) {
            $user->tim_id = null; // Operator
        } else {
            $user->tim_id = $user->tim_id;
        }

        $user->save();

        return redirect()->back()->with('success', 'Role user berhasil diperbarui.');
    }

    public function updateTimUser(Request $request, $id)
    {
        $request->validate([
            'tim_id' => 'nullable|exists:tims,id',
        ]);

        $user = User::findOrFail($id);

        // Kalau role = Ketua (id_role == 2), cek apakah tim sudah punya ketua
        if ($user->id_role == 2 && $request->tim_id) {
            $sudahAdaKetua = User::where('tim_id', $request->tim_id)
                ->where('id_role', 2)
                ->where('id', '!=', $user->id) // exclude dirinya sendiri
                ->exists();

            if ($sudahAdaKetua) {
                return redirect()->back()->with('error', 'Tim ini sudah memiliki Ketua!');
            }
        }

        // Kalau role = Admin (id_role == 3), tim tetap default ke 9
        if ($user->id_role == 3) {
            $user->tim_id = 9;
        }
        // Kalau role = Operator (id_role == 1), tim_id jadi null
        elseif ($user->id_role == 1) {
            $user->tim_id = null;
        } else {
            $user->tim_id = $request->tim_id;
        }

        $user->save();

        return redirect()->back()->with('success', 'Tim user berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if (!$user) {
            return redirect()->route('manage.user.index')->with('error', 'User tidak ditemukan.');
        }

        $user->delete();

        $pegawai = Pegawai::where('nip_lama', $user->nip_lama)->first();

        if ($pegawai) {
            $pegawai->delete();
        }

        return redirect()->route('manage.user.index')->with('success', 'User berhasil dihapus.');
    }
}
