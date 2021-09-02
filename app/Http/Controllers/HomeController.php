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
}
