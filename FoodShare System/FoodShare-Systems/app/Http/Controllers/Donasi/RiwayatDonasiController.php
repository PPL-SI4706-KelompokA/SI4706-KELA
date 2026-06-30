<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Donasi;

class RiwayatDonasiController extends Controller
{
    public function index()
    {
        $donasi = Donasi::latest()->get(); // ambil semua data

        return view('donasi.riwayat', compact('donasi'));
    }
}