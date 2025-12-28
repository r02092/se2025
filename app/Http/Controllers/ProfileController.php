<?php

namespace App\Http\Controllers;

se Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // MM00 利用者モジュール

/**
 * MC08: プロフィール画面構成モジュール
 * 概要: Scene Trip のプロフィール画面を構成する 
 */
class ProfileController extends Controller
{
	/**
	 * プロフィール情報の表示処理
	 * 手順: プロフィールを利用者モジュールを用いて読み込み、画面出力モジュールを呼び出す 
	 */
	public function show()
	{
		// 1. 利用者モジュールを用いて、現在ログイン中の利用者情報を取得 
		// Auth::user() は設計書で指定された Laravel Socialite 等による認証済みの User モデルを返す [cite: 13, 101]
		$user = Auth::user();

		// 2. プロフィール画面出力モジュール (MV04) を呼び出し 
		// resources/views/profile.blade.php を構成して応答する 
		return view('profile', [
			'user' => $user
		]);
	}
}
2. ビューモジュール (resources/views/profile.blade.php)
「MV04 アカウント情報確認画面」の定義に基づきます 。コーディング規約に従い、インデントにはタブを使用します 。
+1

HTML

@extends('layouts.app') {{-- MV00 画面の共通部分出力モジュールを呼び出し [cite: 84, 91] --}}

@section('content')
<div class="profile-container">
	<h2>アカウント情報確認</h2>

	<div class="profile-card">
		{{-- アイコンの表示 (icon_ext カラムを使用) [cite: 38] --}}
		<div class="profile-icon">
			@if($user->icon_ext)
				<img src="/storage/icons/user_{{ $user->id }}.{{ $user->icon_ext }}" alt="プロフィールアイコン">
			@else
				<img src="/assets/img/default-icon.png" alt="デフォルトアイコン">
			@endif
		</div>

		<div class="profile-details">
			{{-- 名前 (name) [cite: 38] --}}
			<div class="detail-item">
				<span class="label">ユーザー名:</span>
				<span class="value">{{ $user->name }}</span>
			</div>

			{{-- ログイン名 (login_name) [cite: 38] --}}
			<div class="detail-item">
				<span class="label">ログイン名:</span>
				<span class="value">{{ $user->login_name }}</span>
			</div>

			{{-- 権限種別 (permission) の表示 [cite: 40] --}}
			<div class="detail-item">
				<span class="label">アカウント種別:</span>
				<span class="value">
					@if($user->permission == 0) 管理者
					@elseif($user->permission == 1) 利用者
					@elseif($user->permission == 2) 承認済み事業者
					@endif
				</span>
			</div>

			{{-- 事業者の場合のみ住所等を表示 (設計書 1.4.1/1.4.3 に関連) [cite: 10, 11] --}}
			@if($user->permission == 2)
				<div class="detail-item">
					<span class="label">所在地:</span>
					<span class="value">〒{{ $user->postal_code }} {{ $user->addr_detail }}</span>
				</div>
			@endif
		</div>
	</div>

	{{-- 他モジュールへの遷移導線 (MC08 の責務に含まれる画面構成要素) --}}
	<div class="profile-actions">
		{{-- プロフィール編集画面 (MC09/MV05) への遷移 [cite: 93, 102] --}}
		<a href="{{ route('profile.edit') }}" class="btn">情報を編集する</a>

		{{-- 二要素認証設定 (MC12) への遷移  --}}
		<a href="{{ route('two-factor.index') }}" class="btn-auth">二要素認証の設定</a>
	</div>
</div>
@endsection