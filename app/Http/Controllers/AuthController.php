<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $data = $request->all();

        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return redirect()->route('home')
            ->with('erro', 'Usuário e/ou senha não conferem');
        }

        $hashUserPassword = $user->password;

        if (!password_verify($data['password'], $hashUserPassword)) {
            return response()->json([
                'ok' => false,
                'message' => 'Usuário e/ou senha inválidos! Verifique os dados informados e tente novamente.'
            ]);
        }

        if (isset($data['remember'])) {
            $expira = time() + (60 * 60 * 24 * 30); // 30 days

            setcookie('password', $data['password'], $expira);
            setcookie('email', $data['email'], $expira);
        } else {
            setcookie('password', '', time() -1);
            setcookie('email', '', time() -1);
        }

        $request->session()->put('user', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'firstAccess' => $user->first_access
        ]);

        if ($user->first_access == 1) {
            return response()->json([
                'ok' => true,
                'dashboard' => false
            ]);
        }

        return response()->json([
            'ok' => true,
            'dashboard' => true
        ]);
    }

    public function logout()
    {
        session()->forget('user');

        return redirect()->route('home');
    }
}
