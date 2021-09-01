<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'max:100', 'string'],
            'password' => ['required']
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return redirect()->route('home')
            ->with('erro', 'Usuário e/ou senha não conferem');
        }

        $hashUserPassword = $user->password;

        if (!password_verify($data['password'], $hashUserPassword)) {
            return redirect()->route('home')
                ->with('erro', 'Usuário e/ou senha não conferem');
        }

        $request->session()->put('user', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'firstAccess' => $user->first_access
        ]);

        if ($user->first_access == 1) {
            return redirect()->route('initial');
        }

        return redirect()->route('dashboard');
    }

    public function logout()
    {
        session()->forget('user');

        return redirect()->route('home');
    }
}
