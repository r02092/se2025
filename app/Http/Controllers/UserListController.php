<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class UserListController extends Controller
{
    public function index(): View
    {
        return view('user');
    }
}
