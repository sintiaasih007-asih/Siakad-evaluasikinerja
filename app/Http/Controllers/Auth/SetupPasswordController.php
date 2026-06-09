<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class SetupPasswordController extends Controller
{
    public function form($token)
    {
        $data = DB::table('user_setup_tokens')
            ->where('token', $token)
            ->where('expired_at', '>', now())
            ->first();

        abort_if(!$data, 404);

        return view('auth.setup-password', compact('token'));
    }

    public function save(Request $request, $token)
    {
        $request->validate([
            'password' => [
                'required','confirmed','min:8',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*#?&]/'
            ]
        ]);

        $data = DB::table('user_setup_tokens')->where('token', $token)->first();
        abort_if(!$data, 404);

        User::where('id', $data->user_id)->update([
            'password' => Hash::make($request->password),
            'is_active' => true
        ]);

        DB::table('user_setup_tokens')
            ->where('token', $token)
            ->delete();

        return redirect('/login')->with('success', 'Password berhasil dibuat');
    }
}