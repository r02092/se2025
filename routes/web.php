<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccountCreateController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\ReviewController; // 追加: MU15
use App\Http\Controllers\SearchController; // 追加: MC00
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\InvoiceController;

// ホームページ(MC00:人気スポットロジックを使用)
Route::get('/', [SearchController::class, 'index'])->name('home');
// 公開ページ（ログイン不要）
Route::get('/post', function () {
    return view('post');
})->name('post');

Route::get('/coupon', function () {
    return view('coupon');
})->name('coupon');

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

// 認証関連
Route::get('/login', [LoginController::class, 'index'])->name('login');

Route::post('/login', [LoginController::class, 'authenticate'])->name(
    'login.post',
);

Route::get('/signup', function () {
    return view('signup');
})->name('signup');

Route::post('/signup', [AccountCreateController::class, 'post'])->name(
    'signup.post',
);

Route::get('/2fa', function () {
    return '2FA認証画面'; // 仮
})->name('2fa.index');

Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/api', [ApiController::class, 'get'])->name('api');

// 認証が必要なルート
Route::middleware(['auth'])->group(function () {
    // ▼▼▼ 追加部分 (MU15: 口コミ投稿) ▼▼▼
    Route::post('/reviews', [ReviewController::class, 'store'])->name(
        'reviews.store',
    );
    // プロフィール
    Route::get('/profile', function () {
        return view('profile');
    })->name('profile');

    Route::get('/profile/edit', function () {
        return view('profile-edit');
    })->name('profile.edit');

    Route::put('/profile', function () {
        // プロフィール更新処理
    })->name('profile.update');

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
    ])->name('Subscription.store');
    Route::get('/subscription/confirm', [
        SubscriptionController::class,
        'confirm',
    ])->name('subscription.confirm');

    // プロフィール二要素認証
    Route::get('/profile/2fa', function () {
        return view('profile-2fa');
    })->name('profile.2fa');
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

        Route::get('/api-keys', function () {
            return view('business.api');
        })->name('business.api');

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

        Route::get('/users', function () {
            return view('admin.users');
        })->name('admin.users');

        Route::get('/ugc', function () {
            return view('admin.ugc');
        })->name('admin.ugc');

        Route::get('/spots', function () {
            return view('admin.spots');
        })->name('admin.spots');

        Route::get('/data', function () {
            return view('admin.data');
        })->name('admin.data');

        Route::get('/user/{id}', function ($id) {
            return view('admin.user-detail');
        })->name('user.detail');
    });

// 検索
Route::get('/search', function () {
    return view('search');
})->name('search');
