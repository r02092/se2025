@extends('layouts.app')

@section('title', '事業者申込フォーム')

@section('content')
<div class="main-area">
    <div class="general-box subscription-form-container">
        <h2>事業者申込フォーム</h2>
        <form method="POST" action="{{ route('subscription.store') }}">
            @csrf

            <label for="post-code">郵便番号(ハイフン無し)</label>
            <input type="text" id="post-code" name="post_code" required value="{{ old('post_code') }}" />

            <label for="address">住所</label>
            <input type="text" id="address" name="address" required value="{{ old('address') }}" />

            <label for="plan-select">プラン</label>
            <select name="plan" id="plan-select" required>
                <option value="">--1つ選択してください--</option>
                <option value="standard" {{ old('plan') == 'standard' ? 'selected' : '' }}>スタンダードプラン</option>
                <option value="premium" {{ old('plan') == 'premium' ? 'selected' : '' }}>プレミアムプラン</option>
            </select>

            <button type="submit">
                申し込む
            </button>
        </form>
    </div>
</div>
@endsection
