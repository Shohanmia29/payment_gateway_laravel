<?php

namespace App\Http\Controllers;

use App\Lib\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $cards = [];
        return view('dashboard', compact('cards'));
    }
}
