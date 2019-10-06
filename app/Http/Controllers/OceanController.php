<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Ocean;

class OceanController extends Controller
{
    public function index()
    {
        $oceans = Ocean::all();
        return view('index', ['oceans' => $oceans]);
    }
}
