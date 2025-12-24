<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCoupon extends Model
{
    protected $casts = [
        'id' => 'int',
        'coupon_id' => 'int',
        'user_id' => 'int',
        'key' => 'int',
        'is_used' => 'bool',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    protected $guarded = ['id'];
    public function coupon()
    {
        return $this->belongsTo('App\Models\Coupon');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
