<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use app\Traits\TransformCoordTrait;

class Photo extends Model
{
    use TransformCoordTrait;

    protected $casts = [
        'id' => 'int',
        'user_id' => 'int',
        'lng' => 'int',
        'lat' => 'int',
        'img_ext' => 'string',
        'comment' => 'string',
        'ip_addr' => 'string',
        'port' => 'int',
        'user_agent' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    protected $guarded = ['id'];
    public function user()
    {
        return $this->belongsTo(User::class);
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
