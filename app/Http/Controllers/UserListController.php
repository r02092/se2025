<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class UserListController extends Controller
{
    public function index(): View
    {
        $users = \App\Models\User::all();
        return view('user', compact('users'));
    }
}
