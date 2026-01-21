@extends('layouts.app')

@section('title', 'SceneTrip - ホーム')

@push('styles')
@endpush

@section('content')
<div class="map-area">
    <div id="map"></div>
</div>

<div class="general-box form-container" style="padding-top: 15px; padding-bottom: 20px; margin: 16px 5% 16px">
    {{-- ▼ 修正: action, methodを削除し、idを追加 --}}
    <form id="search-form">
        <div style="margin-bottom: 10px;">
            <label for="departure" style="font-weight:bold;">出発地</label>
            <span style="font-size: 0.8rem; color: #666; margin-left: 5px;"></span>

            <input type="text" id="departure" name="departure" placeholder="例: 高知駅"
                   style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px; margin-top:5px;" />
        </div>

        <div style="margin-bottom: 15px;">
            <label for="destination" style="font-weight:bold;">目的地</label>
            {{-- id="destination" はJSで使用します --}}
            <input type="text" id="destination" name="destination" placeholder="作品名・地名はここに入力してください"
                   style="width:100%; padding:8px; border:1px solid #ccc; border-radius:4px; margin-top:5px;" required />
        </div>

        <button type="submit" class="btn-green" style="width:100%; padding:10px; border:none; cursor:pointer;">
            検索
        </button>
    </form>
</div>

<div class="general-box ai-suggest" style="padding-bottom: auto;">
    <h2>検索結果</h2>
    <div class="spot-divider" aria-hidden="true"></div>

    {{-- ▼ 修正: JSで操作するために id="spot-list" を追加 --}}
    <ul class="spot-list" id="spot-list" aria-label="人気のスポット一覧">

        {{-- 初期表示（サーバーサイドレンダリング） --}}
        @if(isset($spots) && count($spots) > 0)
            @foreach($spots as $spot)
                <li class="spot-item">
                    <img class="spot-thumb"
                         src="{{ asset('images/' . $spot->name . '.' . ($spot->img_ext ?? 'jpg')) }}"
                         alt="{{ $spot->name }}"
                         onerror="this.src='https://placehold.jp/150x150.png?text=No+Image'" />
                    <div class="spot-content">
                        <h3 class="spot-title">{{ $spot->name }}</h3>
                        <p>{{ $spot->description }}</p>
                        <a href="{{ route('spot.detail', $spot->id) }}" style="color:#16a34a; font-size:0.9rem;">詳細を見る</a>
                    </div>
                </li>
            @endforeach
        @else
            <li class="spot-item">
                <div class="spot-content">
                    <p>検索ワードを入力してください。</p>
                </div>
            </li>
        @endif

    </ul>
</div>

<div class="suggest"></div>

{{-- ▼▼▼ JavaScriptを追加 ▼▼▼ --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('search-form');
    const input = document.getElementById('destination');
    const list = document.getElementById('spot-list');

    form.addEventListener('submit', function(e) {
        e.preventDefault(); // 画面遷移をブロック

        const val = input.value;

        // APIへリクエスト (パラメータ名は keyword に統一)
        fetch(`/api/search?keyword=${encodeURIComponent(val)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // リストをクリア
                list.innerHTML = '';

                // オブジェクト形式で返ってくる場合に備えて配列化
                const spots = Object.values(data);

                if (spots.length === 0) {
                    list.innerHTML = '<li class="spot-item"><div class="spot-content"><p>条件に一致するスポットは見つかりませんでした。</p></div></li>';
                    return;
                }

                // 取得したデータでリストを再生成
                spots.forEach(spot => {
                    // 画像パスの生成 (img_extがない場合はpngとする)
                    const ext = spot.img_ext ? spot.img_ext : 'png';
                    const imgPath = `/images/${spot.name}.${ext}`;

                    // 詳細画面へのリンク (route('spot.detail', id) 相当のパス)
                    const detailLink = `/spot/${spot.id}`;

                    const html = `
                        <li class="spot-item">
                            <img class="spot-thumb"
                                 src="${imgPath}"
                                 alt="${spot.name}"
                                 onerror="this.src='https://placehold.jp/150x150.png?text=No+Image'" />
                            <div class="spot-content">
                                <h3 class="spot-title">${spot.name}</h3>
                                <p>${spot.description || ''}</p>
                                <a href="${detailLink}" style="color:#16a34a; font-size:0.9rem;">詳細を見る</a>
                            </div>
                        </li>
                    `;
                    list
