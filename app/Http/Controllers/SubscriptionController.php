<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Traits\ToStringTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;

class SubscriptionController extends Controller
{
    use ToStringTrait;

    public function create(): View
    {
        return view('subscription-form', $this->selectPrefsCities());
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'post_code' => ['required', 'digits:7'], // ハイフンなし7桁
            'city' => ['required', 'integer', 'between:1100,47999'],
            'address' => ['required', 'string', 'max:255'],
            'num_plan_std' => ['required', 'integer', 'between:0,4294967295'],
            'num_plan_prm' => ['required', 'integer', 'between:0,4294967295'],
        ]);

        $user = Auth::user();

        $user->postal_code = (int) $validated['post_code'];
        $user->addr_city = $validated['city'];
        $user->addr_detail = $validated['address'];
        $user->num_plan_std = (int) $validated['num_plan_std'];
        $user->num_plan_prm = (int) $validated['num_plan_prm'];

        $user->permission = 2;

        $user->save();

        return redirect()->route('subscription.confirm');
    }

    public function confirm(): View
    {
        return view('subscription-confirm');
    }
}
