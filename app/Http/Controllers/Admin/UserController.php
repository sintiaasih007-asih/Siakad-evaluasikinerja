<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendSetupPasswordMail;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */
    public function index(Request $request)
    {
        $search = $request->search;

        $users = User::with(['guru', 'siswa'])
            ->when($search, function ($q) use ($search) {
                $q->where('email', 'like', "%$search%")
                  ->orWhere('name', 'like', "%$search%");
            })
            ->latest()
            ->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */
    public function create()
    {
        $allGuru = \App\Models\Guru::all();

        $guruWaliKelas = \App\Models\Guru::whereIn('id', function ($q) {
            $q->select('guru_id')
            ->from('kelas')
            ->whereNotNull('guru_id');
        })->get();

        $siswas = \App\Models\Siswa::all();

        return view('admin.users.create', compact('allGuru', 'guruWaliKelas', 'siswas'));
    }

    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'role'  => 'required',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Tentukan Nama User Otomatis
        |--------------------------------------------------------------------------
        */
        $name = null;
        $guruId = null;
        $siswaId = null;

        // ROLE GURU / ADMIN / KEPALA SEKOLAH / WALI KELAS
        if (in_array($request->role, ['admin','guru','kepala_sekolah','guru&wali_kelas'])) {

            $guru = \App\Models\Guru::find($request->guru_id);

            if ($guru) {
                $name = $guru->nama;
                $guruId = $guru->id;
            }
        }

        // ROLE ORANG TUA
        if ($request->role === 'orang_tua') {
            $siswa = \App\Models\Siswa::find($request->siswa_id);

        if ($siswa) {
            $name = $siswa->nama_ayah ?? $siswa->nama_ibu ?? $siswa->nama;
            $siswaId = $siswa->id;
        }
        }

        /*
        |--------------------------------------------------------------------------
        | Simpan User
        |--------------------------------------------------------------------------
        */
        \App\Models\User::create([
            'name' => $name ?? $request->email,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role, // 🔥 JANGAN PERNAH DIUBAH
            'guru_id' => $guruId,
            'siswa_id' => $siswaId,
            'is_active' => 1,
        ]);

        // $this->sendToken($user);

        return redirect()->route('users.index')
        ->with('success', 'User berhasil dibuat');
    }

    /*
    |--------------------------------------------------------------------------
    | SEND TOKEN EMAIL
    |--------------------------------------------------------------------------
    */
    private function sendToken($user)
    {
        DB::table('user_setup_tokens')
            ->where('user_id', $user->id)
            ->delete();

        $token = Str::random(64);

        DB::table('user_setup_tokens')->insert([
            'user_id'    => $user->id,
            'token'      => $token,
            'expired_at' => now()->addDay(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        Mail::to($user->email)
            ->send(new SendSetupPasswordMail($token));
    }

    /*
    |--------------------------------------------------------------------------
    | RESEND EMAIL
    |--------------------------------------------------------------------------
    */
    public function resend($id)
    {
        $user = User::findOrFail($id);

        $this->sendToken($user);

        return back()->with('success', 'Email setup password dikirim ulang');
    }

    /*
    |--------------------------------------------------------------------------
    | TOGGLE ACTIVE
    |--------------------------------------------------------------------------
    */
    public function toggle($id)
    {
        $user = User::findOrFail($id);

        $user->is_active = !$user->is_active;
        $user->save();

        return back()->with('success', 'Status user diperbarui');
    }

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */
    public function destroy($id)
    {
        DB::table('user_setup_tokens')
            ->where('user_id', $id)
            ->delete();

        User::findOrFail($id)->delete();

        return back()->with('success', 'User berhasil dihapus');
    }
}