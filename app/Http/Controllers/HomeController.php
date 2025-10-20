<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Здесь можно потом подгружать товары, баннеры и т.д.
        return view('home');
    }
}
