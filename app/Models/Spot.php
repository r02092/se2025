<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
    protected $casts = [
        'id' => 'int',
        'spot_id' => 'int',
        'keyword' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    protected $guarded = ['id'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function keywords()
    {
        return $this->hasMany(Keyword::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }
    public function stamps()
    {
        return $this->hasMany(Stamp::class);
    }
}
