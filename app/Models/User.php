<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/** 利用者モジュール(MM00)
 * データベースの users テーブルのデータの読み込みおよび書き込みを行う
 */
class User extends Model
{
    protected $casts = [
        'id' => 'int',
        'provider' => 'int',
        'login_name' => 'int',
        'password' => 'int',
        'permission' => 'int',
        'name' => 'string',
        'icon_ext' => 'string',
        'num_plan_std' => 'int',
        'num_plan_prm' => 'int',
        'postal_code' => 'int',
        'addr_city' => 'int',
        'addr_detail' => 'string',
        'totp' => 'bool',
        'totp_iv' => 'bool',
        'totp_tag' => 'bool',
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
}
