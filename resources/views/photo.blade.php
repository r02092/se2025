@extends('layouts.app')

@section('title', 'SceneTrip - フォト')

@section('content')
<!-- グラデーション トップ -->
<div class="gradient-top"></div>

<div class="map-area" style="padding: 0; height: 100vh">
	<div id="map" style="height: calc(100% - 97px)"></div>
</div>
@endsection
