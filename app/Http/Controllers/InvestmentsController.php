<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InvestmentsController extends Controller
{
    public function index()
    {
        return view('investments.index');
    }
}
