<?php

namespace App\Http\Controllers;

use App\Http\Requests\TwoFactorRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    public function index(): View
    {
        return view('auth.2fa');
    }

    public function verify(TwoFactorRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $code = $request->input('one_time_password');

        $google2fa = app('pragmarx.google2fa');
        $valid = $google2fa->verifyKey($user->totp_secret, $code, 1);

        if ($valid) {
            $request->session()->forget('auth.2fa_required');
            return redirect()->route('home');
        }

        return back()->withErrors([
            'one_time_password' => '認証コードが正しくありません。',
        ]);
    }
}
