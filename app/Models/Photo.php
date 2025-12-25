<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
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
