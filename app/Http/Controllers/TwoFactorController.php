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
        if (!session()->has('login.2fa_user_id')) {
            return redirect()->route('login');
        }
        return view('2fa');
    }

    public function verify(TwoFactorRequest $request): RedirectResponse
    {
        $userId = session('login.2fa_user_id');
        if (!$userId) {
            return redirect()->route('login');
        }
        
        $user = \App\Models\User::find($userId);
        if (!$user) {
             return redirect()->route('login');
        }

        $code = $request->input('one_time_password');

        $google2fa = app('pragmarx.google2fa');
        $valid = $google2fa->verifyKey($user->totp_secret, $code, 1);

        if ($valid) {
            Auth::login($user);
            session()->regenerate();
            session()->forget('login.2fa_user_id');
            return redirect()->route('home');
        }

        return back()->withErrors([
            'one_time_password' => '認証コードが正しくありません。',
        ]);
    }
}
