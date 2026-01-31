<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Stamp;

class FunpageController extends Controller
{
    public function get()
    {
        return view('funpage', [
            'stamps' => Stamp::where('user_id', Auth::id())
                ->with('spot')
                ->get(),
        ]);
    }
}
