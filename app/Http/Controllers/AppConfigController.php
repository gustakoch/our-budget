<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;

class AppConfigController extends Controller
{
    public function store()
    {
        $data = request()->all();

        User::where('id', session('user')['id'])
            ->update([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);

        if ($data['password']) {
            User::where('id', session('user')['id'])
                ->update([
                    'password' => password_hash($data['password'], PASSWORD_DEFAULT),
                ]);
        }

        $user = User::find(session('user')['id']);

        session()->put('user', [
            'id' => $user->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $user->role,
            'firstAccess' => $user->first_access
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Configuração salva com sucesso.'
        ]);
    }
}
