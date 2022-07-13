<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        if ($this->user->isLoggedIn()) {
            return redirect()->route('dashboard');
        }

        return view('login');
    }

    public function register()
    {
        return view('register');
    }

    public function newUser()
    {
        $data = request()->all();

        $user = User::where('email', $data['email'])->first();

        if ($user) {
            return response()->json([
                'ok' => false,
                'message' => 'O e-mail informado já se encontra cadastrado no nosso sistema. Por favor, tente novamente.'
            ]);
        }

        User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'role' => 3,
            'first_access' => 1,
            'active' => 0
        ]);

        return response()->json([
            'ok' => true,
            'message' => 'Sua conta foi criada com êxito e será ativada dentro das próximas 24 horas.'
        ]);
    }
}
