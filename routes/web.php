<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController; // 追加: MC00
use App\Http\Controllers\SearchApiController;
use App\Http\Controllers\PostMapController;
use App\Http\Controllers\DetailController;
use App\Http\Controllers\CouponController;
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

Route::get('/detail', [DetailController::class, 'index'])->name('detail');

Route::get('/coupon', [CouponController::class, 'get'])->name('coupon');

Route::get('/coupon/{id}', function ($id) {
    return view('coupon-selected');
})->name('coupon.show');

Route::get('/coupon/{id}/qr', function ($id) {
    return view('coupon-qr');
})->name('coupon.qr');

Route::get('/funpage', function () {
    return view('funpage');
})->name('funpage');

Route::get('/funpage/checkin', function () {
    return view('funpage-checkin');
})->name('funpage.checkin');

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

    // 2. プロフィール情報の更新 (PUTまたはPOST)
    // ※HTMLフォームから送る場合は method_field('PUT') を使うため PUT にしています
    Route::put('/profile/edit', [ProfileEditController::class, 'update'])->name(
        'profile.update',
    );

    // 3. アイコン画像のアップロード (POST)
    Route::post('/profile/edit/icon', [
        ProfileEditController::class,
        'uploadIcon',
    ])->name('profile.icon.update');

    // 投稿（削除などの操作のみ認証）
    Route::delete('/post/{id}', function ($id) {
        // 投稿削除処理
    })->name('post.delete');

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

    Route::post('/ai', [AiApiController::class, 'post'])->name('ai');
});

// 事業者専用ルート
Route::middleware(['auth'])
    ->prefix('business')
    ->group(function () {
        Route::get('/', function () {
            return view('business');
        })->name('business');

        Route::get('/spots', function () {
            return view('business.spots');
        })->name('business.spots');

        Route::get('/data', function () {
            return view('business.data');
        })->name('business.data');

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

        Route::get('/spots', function () {
            return view('admin.spots');
        })->name('admin.spots');

        Route::get('/data', function () {
            return view('admin.data');
        })->name('admin.data');

        Route::get('/user/{id}', function ($id) {
            return view('admin.user-detail');
        })->name('user.detail');

        Route::resource('users', InsertUserController::class)
            ->only(['create', 'store'])
            ->names('admin.users');
    });

// 検索
// SearchControllerのsearchメソッドを使って検索を実行し、履歴を保存します
Route::get('/search', [SearchController::class, 'search'])->name('search');
