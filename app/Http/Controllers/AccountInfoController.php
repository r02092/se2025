<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

/**
 * アカウント情報確認画面構成モジュール (MV04)
 *
 * 概要: 他ユーザーには表示されないアカウントの非公開情報を表示する
 */
class AccountInfoController extends Controller
{
    /**
     * アカウント情報確認画面を表示する
     *
     * @param Request $request 利用者から受け取った HTTP リクエスト
     * @return View Web ページ
     */
    public function show(Request $request): View
    {
        $user = Auth::user();

        return view('account_info', ['user' => $user]);
    }
}