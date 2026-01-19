<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\View\View;
use App\Http\Controllers\Controller;

class TermsController extends Controller
{
    public function index(): View
    {
        return view('terms');
    }
}
