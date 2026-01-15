@extends('layouts.app')

@section('title', 'SceneTrip - ホーム')

@push('styles')
@endpush

@section('content')
<!-- グラデーション トップ -->

<div class="map-area">
    <div id="map"></div>
</div>

<div class="general-box form-container" style="padding-top: 15px; padding-bottom: 20px; margin: 16px 5% 16px">
    <form action="{{ route('search') }}" method="GET">
        <label for="departure">出発地</label>
        <input type="text" id="departure" name="departure" />

        <label for="destination">到着地</label>
        <input type="text" id="destination" name="destination" />

        <button type="submit" style="margin: 10px 0 0">検索</button>
    </form>
</div>

<div class="general-box ai-suggest" style="padding-bottom: 100px;">
    <h2>人気のスポット</h2>
    <div class="spot-divider" aria-hidden="true"></div>

    <ul class="spot-list" aria-label="人気のスポット一覧">

        {{-- コントローラーから $spots データが渡ってきているかチェック --}}
        @if(isset($spots) && count($spots) > 0)
            @foreach($spots as $spot)
                <li class="spot-item">
                    {{-- 画像パスがあればそれを、なければデフォルト画像（例:はりまや橋）を表示 --}}
                    <img class="spot-thumb"
                         src="{{ asset($spot->image_path ?? 'images/Harimaya_Bridge.jpg') }}"
                         alt="{{ $spot->name }}"
                         {{-- 画像読み込み失敗時のフォールバック --}}
                         onerror="this.src='{{ asset('images/Harimaya_Bridge.jpg') }}'" />

                    <div class="spot-content">
                        <h3 class="spot-title">{{ $spot->name }}</h3>
                        {{-- 検索回数を表示したい場合はコメントアウトを外してください --}}
                        {{-- <p style="font-size:0.8rem; color:#16a34a;">検索数: {{ $spot->search_count }}回</p> --}}
                    </div>
                </li>
            @endforeach
        @else
            {{-- データがまだ1件もない場合の表示 --}}
            <li class="spot-item">
                <div class="spot-content">
                    <h3 class="spot-title">データ集計中...</h3>
                    <p>いろいろな場所を検索してみてください。</p>
                </div>
            </li>
        @endif

    </ul>
</div>

<div class="suggest"></div>
@endsection
