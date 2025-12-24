<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Photo extends Model
{
    protected array $casts = [
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
    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }
    public function getLngAttribute(int $value): float
    {
        return ($value * 360) / (2 ** 32 - 1) - 180;
    }
    public function getLatAttribute(int $value): float
    {
        return ($value * 180) / (2 ** 32 - 1) - 90;
    }
    public function setLngAttribute(int $value): float
    {
        $this->attributes['lng'] = (($value + 180) * (2 ** 32 - 1)) / 360;
    }
    public function setLatAttribute(int $value): float
    {
        $this->attributes['lat'] = (($value + 90) * (2 ** 32 - 1)) / 180;
    }
}
