<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $casts = [
        'id' => 'int',
        'spot_id' => 'int',
        'name' => 'string',
        'cond_spot_id' => 'int',
        'expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
    protected $guarded = ['id'];
    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }
    public function userCoupons()
    {
        return $this->hasMany(UserCoupon::class);
    }
}