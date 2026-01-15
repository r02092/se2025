<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SubscriptionController extends Controller
{
    public function create(): View
    {
        return view('subscription-form');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'post_code' => ['required', 'digits:7'], // ハイフンなし7桁
            'address' => ['required', 'string', 'max:255'],
            'plan' => ['required', 'in:standard,premium'],
        ]);

        $user = Auth::user();

        $user->postal_code = (int) $validated['post_code'];
        $user->addr_detail = $validated['address'];

        $user->permission = 2;

        $user->save();

        return redirect()->route('subscription.confirm');
    }

    public function confirm(): View
    {
        return view('subscription-confirm');
    }
}
