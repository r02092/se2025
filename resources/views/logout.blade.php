@extends('layouts.app')

@section('title', 'SceneTrip - ログアウト確認')

@section('content')
<h1>ログアウト確認</h1>
<section class="general-box achievement" aria-labelledby="achievement-title">
    <h2 id="achievement-title">ログアウトしますか？</h2>
	<div style="text-align: center;">「はい」をクリックすると、ログアウトしてトップページに戻ります。<br></div>
    <div class="general-box divider" aria-hidden="true"></div>

    <div style="display: flex; gap: 12px; justify-content: center; margin-top: 20px;">
        <form method="POST" action="{{ route('logout.confirm') }}" style="display: inline;">
            @csrf
            <button type="submit" class="btn btn-primary" style="background-color: red; color: white; padding: 10px 24px; border: none; border-radius: 4px; cursor: pointer;">
                はい
            </button>
        </form>
        <button type="button" class="btn btn-secondary" style="background-color: #6c757d; color: white; padding: 10px 24px; border: none; border-radius: 4px; cursor: pointer;" onclick="location.href = '{{ route('profile') }}'">
            いいえ
        </button>
    </div>
</section>
@endsection
