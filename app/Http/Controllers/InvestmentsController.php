<?php

namespace App\Http\Controllers;

class InvestmentsController extends Controller
{
    public function index()
    {
        return view('investments.index');
    }
}
