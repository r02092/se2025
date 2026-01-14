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

<div class="general-box ai-suggest">
    <h2>人気のスポット</h2>
    <div class="spot-divider" aria-hidden="true"></div>

    <ul class="spot-list" aria-label="人気のスポット一覧">
        <li class="spot-item">
            <img class="spot-thumb" src="{{ asset('images/Harimaya_Bridge.jpg') }}" alt="はりまや橋" />
            <div class="spot-content">
                <h3 class="spot-title">はりまや橋</h3>
            </div>
        </li>
        <li class="spot-item">
            <img class="spot-thumb" src="{{ asset('images/post-station.jpg') }}" alt="土佐山田駅" />
            <div class="spot-content">
                <h3 class="spot-title">土佐山田駅</h3>
            </div>
        </li>
        <li class="spot-item">
            <img class="spot-thumb" src="{{ asset('images/ryugado.jpg') }}" alt="龍河洞" />
            <div class="spot-content">
                <h3 class="spot-title">龍河洞</h3>
            </div>
        </li>
    </ul>
</div>

<div class="suggest"></div>
@endsection
