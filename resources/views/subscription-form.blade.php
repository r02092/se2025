@extends('layouts.app')

@section('title', '事業者申込フォーム')

@section('content')
<div class="main-area">
    <div class="general-box subscription-form-container">
        <h2>事業者申込フォーム</h2>
        <form>
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

            <button type="submit" onclick="location.href='{{ route('subscription.confirm') }}'">
                申し込む
            </button>

			<button type="button" class="btn btn-secondary" style="background-color: #6c757d; color: white; margin:10px; padding: 10px 24px; border: none; border-radius: 4px; cursor: pointer;" onclick="location.href='{{ route('profile') }}'">
                キャンセル
            </button>
        </form>
    </div>
</div>
@endsection
