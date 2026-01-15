@extends('layouts.app')

@section('title', 'お楽しみ機能')

@section('content')
<div class="main-area">
    <h1>お楽しみ機能</h1>
    <!-- チェックインボックスに data-action を追加 -->
    <div
        class="general-box checkin-box"
        id="checkinBox"
        role="button"
        tabindex="0"
        aria-pressed="false"
        aria-label="チェックイン"
    >
        <svg class="marker-icon" viewBox="0 0 512 512" aria-hidden="true">
            <path
                d="M256,0C159.969,0,82.109,77.859,82.109,173.906c0,100.719,80.016,163.688,123.297,238.719
                C246.813,484.406,246.781,512,256,512s9.188-27.594,50.594-99.375c43.297-75.031,123.297-138,123.297-238.719
                C429.891,77.859,352.031,0,256,0z
                M256,240.406c-36.734,0-66.516-29.781-66.516-66.5c0-36.75,29.781-66.531,66.516-66.531
                s66.516,29.781,66.516,66.531C322.516,210.625,292.734,240.406,256,240.406z"
                fill="#14b888"
            />
        </svg>
        <h2>チェックイン</h2>
    </div>

    <!-- 実績セクション（.achievement h2 をタイトルに使用） -->
    <section
        class="general-box achievement"
        aria-labelledby="achievement-title"
    >
        <h2 id="achievement-title">実績スタンプ</h2>

        <!-- 灰色の線（タイトルの下） -->
        <div class="achievement-divider" aria-hidden="true"></div>

        <!-- バッジ群（4列 × 2行 = 8個） -->
        <div class="achievement-grid" role="list">
            @for($i = 1; $i <= 8; $i++)
            <div class="achievement-item" role="listitem">
                <button class="achievement-badge" aria-label="バッジ{{ $i }}">
                    <span class="badge-inner">{{ $i }}</span>
                </button>
                <div class="achievement-label">スタンプ{{ $i }}</div>
            </div>
            @endfor
        </div>
    </section>
</div>
@endsection
