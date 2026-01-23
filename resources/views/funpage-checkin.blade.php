@extends('layouts.app')

@section('title', 'チェックイン - スキャン')

@section('content')
<div class="main-area">
    <h1>QRコードスキャン</h1>
    
    <div id="checkin-qr-overlay" class="general-box scanner-container checkin-qr-overlay" style="text-align: center; padding: 20px; opacity: 0; transition: opacity 0.3s;">
        {{-- 1. カメラ映像を表示するビデオタグを追加 --}}
        <div class="video-wrapper" style="position: relative; display: inline-block; width: 100%; max-width: 400px; aspect-ratio: 1/1; overflow: hidden; border: 3px solid #14b888; border-radius: 12px; background: #000;">
            <video id="qr-video" style="width: 100%; height: 100%; object-fit: cover;"></video>
            {{-- スキャン範囲を示すガイド枠（任意） --}}
            <div class="scan-guide" style="position: absolute; top: 20%; left: 20%; width: 60%; height: 60%; border: 2px dashed #fff; pointer-events: none;"></div>
        </div>
        
        <p id="scanner-status" style="margin-top: 15px; font-weight: bold;">カメラを起動中...</p>
        
        <div class="button-group" style="margin-top: 20px;">
            <button type="button" class="btn-secondary" onclick="location.href='{{ route('funpage') }}'">
                戻る
            </button>
        </div>
    </div>
</div>

{{-- 2. 内部設計書の「qr-scanner」を制御するJSを読み込み --}}
@vite(['resources/ts/funpage_checkin.ts'])
@endsection