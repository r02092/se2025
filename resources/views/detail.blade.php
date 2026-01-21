@extends('layouts.app')

@section('title', 'SceneTrip - スポット詳細')

@section('content')
<div class="spot-detail-container">

    <!-- スポット基本情報カード -->
    <article class="spot-detail-card">

        <!-- 1. タイトルとカテゴリ -->
        <header class="spot-detail-header">
            <h1 class="spot-detail-title">{{ $spot->name }}</h1>

            <!-- カテゴリ数値(type)を文字列に変換表示 -->
            @php
                $types = [
					0 => '観光',
					1 => '体験アクティビティ',
					2 => 'お土産',
					3 => '飲食',
					4 => '宿泊',
					5 => '公共施設',
					6 => '公共交通機関',
					7 => 'その他'
				];
                $typeLabel = $types[$spot->type];
            @endphp
            <span class="spot-category-badge">{{ $typeLabel }}</span>
        </header>

        <!-- 2. スポット画像 -->
        <div class="spot-detail-image-wrapper">
            <!-- 画像パスの生成例: public/storage/spots/1.jpg -->
            <!-- 画像がない場合の代替画像も設定しておくと安全です -->
            <img src="{{ asset('storage/spots/' . $spot->id . '.' . $spot->img_ext) }}"
                alt="{{ $spot->name }}"
                class="spot-detail-image"
                onerror="this.src='{{ asset('images/no-image.png') }}'">
        </div>

        <div>
            <!-- 3. スポットの説明 -->
            <section class="spot-detail-description-section">
                <h2 class="spot-detail-section-title">スポット詳細</h2>
                <div class="spot-detail-description">
                    <!-- 改行コードを<br>に変換して表示 -->
                    {!! nl2br(e($spot->description)) !!}
                </div>

                <!-- 緯度経度情報の表示（Google Mapsリンクなどにするのも良し） -->
                <!-- <div style="font-size: 0.85rem; color: #888; margin-top: 1rem;">
                    <i class="fas fa-map-marker-alt"></i>
                    位置情報: Lat {{ $spot->lat }} / Lng {{ $spot->lng }}
                </div> -->
            </section>

            <!-- 関連キーワード -->
            @if($spot->keywords->isNotEmpty())
                <div class="spot-detail-keywords-section" style="margin-top: 2rem;">
                    <h2 class="spot-detail-section-title">関連キーワード</h2>
                    <div class="spot-detail-keywords">
                        @foreach($spot->keywords as $keyword)
                            <span class="spot-detail-keyword-tag">{{ $keyword->keyword }}</span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </article>

    <!-- ▼ 口コミ・評価エリア ▼ -->
    <section class="spot-detail-card spot-detail-review-section">
        <h2 class="spot-detail-section-title">口コミ・評判</h2>

        <!-- 4. 評価の平均点表示 -->
        <div class="spot-detail-rating-summary">
            @php
                // 平均評価の計算（Reviewがない場合は0）
                $avgRate = $spot->reviews->avg('rate') ?? 0;
                $starCount = round($avgRate);
            @endphp
            <span class="spot-detail-average-rate">
                <span style="color: #aaa;">平均評価 <span class="review-stars">★</span>{{ number_format($avgRate, 1) }}
            </span>
            <div class="spot-detail-average-label">
                ({{ $spot->reviews->count() }}件のレビュー)
			</div>
        </div>

        <!-- 5. 口コミ一覧 -->
		@if($spot->reviews->isNotEmpty())
        <div class="spot-detail-review-list">
				@foreach($spot->reviews as $review)
                <div class="spot-detail-review-item">
                    <div class="spot-detail-review-header">
                        <!-- ユーザー名（Reviewモデルのuserメソッド経由） -->
                        <span class="spot-detail-review-user">{{ $review->user->name ?? '退会済みユーザー' }}</span>
							<span class="spot-detail-review-date">{{ $review->updated_at->format('Y/m/d') }}</span>
                    </div>
                    <div class="review-stars">
						<!-- 評価の星表示 -->
						@for($i = 1; $i <= 5; $i++)
							@if($i <= $review->rate) ★ @else <span style="color: #ddd;">★</span> @endif
						@endfor
					</div>
                    <div class="spot-detail-review-comment">
                        {!! nl2br(e($review->comment)) !!}
                    </div>
                </div>
				@endforeach
        </div>
		@else
			<p style="text-align: center; color: #555;">まだ口コミはありません。</p>
		@endif

        <!-- 6. 口コミ投稿フォーム -->
        <div class="form-container general-box">
            <h3 style="margin-top: 0; margin-bottom: 1.5rem; font-size: 1.1rem;">口コミを投稿する</h3>

            <!-- ログイン済みの場合のみ表示 -->
            @auth
                <form action="{{ route('reviews.store', $spot->id) }}" method="POST">
                    @csrf
                    <!-- spot_idを送信するためのhidden項目 -->
                    <input type="hidden" name="spot_id" value="{{ $spot->id }}">

                    <div class="spot-detail-form-group">
                        <label for="rate" class="spot-detail-form-label">評価</label>
                        <select name="rate" id="rate" class="spot-detail-form-select" required>
                            <option value="" disabled selected>選択してください</option>
                            <option value="5">★★★★★ (5)</option>
                            <option value="4">★★★★ (4)</option>
                            <option value="3">★★★ (3)</option>
                            <option value="2">★★ (2)</option>
                            <option value="1">★ (1)</option>
                        </select>
                    </div>

                    <div class="spot-detail-form-group">
                        <label for="comment" class="spot-detail-form-label">コメント</label>
                        <textarea name="comment" id="comment" rows="4" class="spot-detail-form-textarea" placeholder="スポットの感想を教えてください" required></textarea>
                    </div>

                    <button type="submit" class="spot-detail-btn-submit">投稿する</button>
                </form>
            @else
                <div style="text-align: center; color: #777;">
                    <p>口コミを投稿するには<a href="{{ route('login') }}" style="color: #3498db;">ログイン</a>が必要です。</p>
                </div>
            @endauth
        </div>

    </section>

</div>
@endsection
