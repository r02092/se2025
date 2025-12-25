<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Spot extends Model
{
    protected $casts = [
        'id' => 'int',
        'user_id' => 'int',
        'plan' => 'int',
        'type' => 'int',
        'name' => 'string',
        'lng' => 'int',
        'lat' => 'int',
        'postal_code' => 'int',
        'addr_city' => 'int',
        'addr_detail' => 'string',
        'description' => 'string',
        'img_ext' => 'string',
        'stamp_key' => 'int',
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
    public function getLngAttribute($value)
    {
        return ($value * 360) / (2 ** 32 - 1) - 180;
    }
    public function getLatAttribute($value)
    {
        return ($value * 180) / (2 ** 32 - 1) - 90;
    }
    public function setLngAttribute($value)
    {
        $this->attributes['lng'] = (($value + 180) * (2 ** 32 - 1)) / 360;
    }
    public function setLatAttribute($value)
    {
        $this->attributes['lat'] = (($value + 90) * (2 ** 32 - 1)) / 180;
    }
}
