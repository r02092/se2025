<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController; // 追加: MC00
use App\Http\Controllers\SearchApiController;
use App\Http\Controllers\PostMapController;
use App\Http\Controllers\PostFormController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CouponSelectedController;
use App\Http\Controllers\CouponApiController;
use App\Http\Controllers\FunpageController;
use App\Http\Controllers\CheckinApiController;
use App\Http\Controllers\TermsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AccountCreateController;
use App\Http\Controllers\TwoFactorController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ReviewController; // 追加: MU15
use App\Http\Controllers\ProfileEditController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\ProfileTwoFactorController;
use App\Http\Controllers\AddrApiController;
use App\Http\Controllers\EditSpotController;
use App\Http\Controllers\DataController;
use App\Http\Controllers\AiApiController;
use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\UserListController;
use App\Http\Controllers\AdminUgcController;
use App\Http\Controllers\InsertUserController;

// ホームページ(MC00:人気スポットロジックを使用)
Route::get('/', [SearchController::class, 'index'])->name('home');

Route::get('/filtering', [SearchApiController::class, 'getSpotList'])->name(
    'home.filtered',
);

// 公開ページ（ログイン不要）
Route::get('/post', [PostMapController::class, 'index'])->name('post');

Route::get('/post/load', [PostMapController::class, 'getPosts'])->name(
    'post.load',
);

Route::get('/post/form', function () {
    return view('photo-form');
})->name('post.form');

Route::post('/post/form', [PostFormController::class, 'post'])->name(
    'post.form.post',
);

Route::get('/detail', [DetailController::class, 'index'])->name('detail');

Route::get('/coupon', [CouponController::class, 'get'])->name('coupon');

Route::get('/coupon/{id}', [CouponSelectedController::class, 'get'])->name(
    'coupon.show',
);

Route::post('/coupon/api', [CouponApiController::class, 'use'])->name(
    'coupon.api',
);

Route::get('/coupon/{id}/qr', function ($id) {
    return view('coupon-qr');
})->name('coupon.qr');

Route::get('/funpage', [FunpageController::class, 'get'])->name('funpage');

Route::get('/funpage/checkin', function () {
    return view('funpage-checkin');
})->name('funpage.checkin');

Route::post('/funpage/checkin/api', [
    CheckinApiController::class,
    '__invoke',
])->name('funpage.checkin.api');

Route::get('/terms', [TermsController::class, 'index'])->name('terms');

// 認証関連
Route::get('/login', [LoginController::class, 'index'])->name('login');

Route::post('/login', [LoginController::class, 'authenticate'])->name(
    'login.post',
);

Route::get('auth/google', [LoginController::class, 'redirectToGoogle'])->name(
    'login.google',
);

Route::get('auth/google/callback', [
    LoginController::class,
    'handleGoogleCallback',
]);

Route::get('/signup', function () {
    return view('signup');
})->name('signup');

Route::post('/signup', [AccountCreateController::class, 'post'])->name(
    'signup.post',
);

Route::get('2fa', [TwoFactorController::class, 'index'])->name('2fa.index');

Route::post('2fa', [TwoFactorController::class, 'verify'])->name('2fa.verify');

Route::get('/logout', [LoginController::class, 'showLogoutForm'])->name(
    'logout',
);
Route::post('/logout', [LoginController::class, 'logout'])->name(
    'logout.confirm',
);

Route::get('/api', [ApiController::class, 'get'])->name('api');

// 認証が必要なルート
Route::middleware(['auth'])->group(function () {
    // ▼▼▼ 追加部分 (MU15: 口コミ投稿) ▼▼▼
    Route::post('/reviews', [ReviewController::class, 'store'])->name(
        'reviews.store',
    );
    // プロフィール
    // --- 修正後 ---
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');
    // 1. プロフィール編集画面の表示 (GET)
    Route::get('/profile/edit', [ProfileEditController::class, 'edit'])->name(
        'profile.edit',
    );

    // 2. プロフィール情報の更新 (POST)
    // ※画像アップロードを含むためPOSTに変更
    Route::post('/profile/edit', [
        ProfileEditController::class,
        'update',
    ])->name('profile.update');

    // 3. アイコン画像のアップロード (POST)
    Route::post('/profile/edit/icon', [
        ProfileEditController::class,
        'uploadIcon',
    ])->name('profile.icon.update');

    // 投稿（削除などの操作のみ認証）
    Route::delete('/post/{id}', function ($id) {
        // 投稿削除処理
    })->name('post.delete');

    // AIプランニング画面
    Route::get('/ai-plan', [SearchController::class, 'aiPlan'])->name(
        'ai.plan',
    );

    // ▼▼▼ 追加: AI検索処理 (Webルートに移動) ▼▼▼
    // JavaScriptから叩くためのルートです
    Route::post('/ai-search', [AiApiController::class, 'post'])->name(
        'ai.search',
    );

    Route::post('/ai', [AiApiController::class, 'post'])->name('ai');

    // お楽しみ機能（操作系があればここに追加）

    // 事業者申込
    Route::get('/subscription', [
        SubscriptionController::class,
        'create',
    ])->name('subscription.form');
    Route::post('/subscription', [
        SubscriptionController::class,
        'store',
    ])->name('subscription.store');
    Route::get('/subscription/confirm', [
        SubscriptionController::class,
        'confirm',
    ])->name('subscription.confirm');

    // プロフィール二要素認証
    Route::get('/profile/2fa', [
        ProfileTwoFactorController::class,
        'index',
    ])->name('profile.2fa');

    Route::post('/profile/2fa', [
        ProfileTwoFactorController::class,
        'store',
    ])->name('profile.2fa.store');

    Route::delete('/profile/2fa', [
        ProfileTwoFactorController::class,
        'destroy',
    ])->name('profile.2fa.destroy');

    Route::get('/addr/{pc}', [AddrApiController::class, 'get'])->name('addr');
});

// 事業者専用ルート
Route::middleware(['auth'])
    ->prefix('business')
    ->group(function () {
        Route::get('/', function () {
            return view('business');
        })->name('business');

        Route::get('/spots/{page}', [EditSpotController::class, 'get'])->name(
            'business.spots',
        );

        Route::post('/spots/update', [
            EditSpotController::class,
            'update',
        ])->name('business.spots.upd');

        Route::post('/spots/delete', [
            EditSpotController::class,
            'delete',
        ])->name('business.spots.del');

        Route::get('/data', [DataController::class, 'get'])->name(
            'business.data',
        );

        Route::get('/api-keys', [ApiKeyController::class, 'get'])->name(
            'business.api',
        );

        Route::post('/api-keys', [ApiKeyController::class, 'post'])->name(
            'business.api.post',
        );

        Route::get('/invoice', [InvoiceController::class, 'get'])->name(
            'business.invoice',
        );
    });

// 管理者専用ルート
Route::middleware(['auth'])
    ->prefix('admin')
    ->group(function () {
        Route::get('/', function () {
            return view('admin');
        })->name('admin');

        Route::get('/users', [UserListController::class, 'index'])->name(
            'admin.users.list',
        );

        Route::get('/ugc/{page}', [AdminUgcController::class, 'get'])->name(
            'admin.ugc',
        );
        Route::post('/ugc/delete', [AdminUgcController::class, 'post'])->name(
            'admin.ugc.del',
        );

        Route::get('/user/{id}', function ($id) {
            return view('admin.user-detail');
        })->name('user.detail');

        Route::resource('users', InsertUserController::class)
            ->only(['create', 'store'])
            ->names('admin.users');
    });

// SearchControllerのsearchメソッドを使って検索を実行し、履歴を保存します
Route::get('/search', [SearchController::class, 'search'])->name('search');

// デフォルトアイコン配信 (Git管理下のSeederファイルを表示)
Route::get('/default-icon', function () {
    $path = base_path('database/seeders/files/icons/1.JPG');
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->file($path);
})->name('default_icon');
