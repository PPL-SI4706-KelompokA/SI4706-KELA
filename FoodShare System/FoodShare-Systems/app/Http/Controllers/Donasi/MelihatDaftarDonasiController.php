<?php

namespace App\Http\Controllers;

use App\Models\Donasi;

class DonasiController extends Controller
{
    public function index()
    {
        $donasi = Donasi::latest()->get();
        return view('donasi.index', compact('donasi'));
    }
}