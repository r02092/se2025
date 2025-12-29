<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\TransformCoordTrait;

class Spot extends Model
{
    use TransformCoordTrait;

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
    public function setLngAttribute($value)
    {
        $this->attributes['lng'] = $this->encodeLng($value);
    }
    public function setLatAttribute($value)
    {
        $this->attributes['lat'] = $this->encodeLat($value);
    }
}
