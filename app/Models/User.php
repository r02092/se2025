<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

/** 利用者モジュール(MM00)
 * データベースの users テーブルのデータの読み込みおよび書き込みを行う
 */
class User extends Authenticatable
{
    const PERMISSION_ADMIN = 0;
    const PERMISSION_USER = 1;
    const PERMISSION_BUSINESS = 2;
    const PROVIDER_SCENETRIP = 1;

    protected $casts = [
        'id' => 'int',
        'provider' => 'int',
        'login_name' => 'string',
        'password' => 'string',
        'permission' => 'int',
        'name' => 'string',
        'icon_ext' => 'string',
        'num_plan_std' => 'int',
        'num_plan_prm' => 'int',
        'postal_code' => 'int',
        'addr_city' => 'int',
        'addr_detail' => 'string',
        'totp_secret' => 'string',
        'totp_iv' => 'string',
        'totp_tag' => 'string',
        'totp_last_name' => 'int',
        'totp_counter' => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    protected $guarded = ['id'];
    public function spots()
    {
        return $this->hasMany(Spot::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function userCoupons()
    {
        return $this->hasMany(UserCoupon::class);
    }
    public function stamps()
    {
        return $this->hasMany(Stamp::class);
    }
    public function queries()
    {
        return $this->hasMany(Query::class);
    }
    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
    public function apiKeys()
    {
        return $this->hasMany(ApiKey::class);
    }
    /**
     * Get the user's icon URL.
     * Uses the user's stored icon if available, otherwise falls back to the default icon.
     *
     * @return string
     */
    public function getIconUrlAttribute()
    {
        if ($this->icon_ext) {
            $path = 'icons/' . $this->id . '.' . $this->icon_ext;
            // ファイルが実際に存在するか確認
            if (
                \Illuminate\Support\Facades\Storage::disk('public')->exists(
                    $path,
                )
            ) {
                // キャッシュ対策として time() を付与
                return asset('storage/' . $path . '?' . time());
            }
        }

        // デフォルトアイコン
        return asset('storage/icons/default_icon.jpg');
    }
}
