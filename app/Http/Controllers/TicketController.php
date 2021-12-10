<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        return view('tickets.index');
    }
}
