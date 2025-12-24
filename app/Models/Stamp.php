<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Stamp extends Model
{
    protected array $casts = [
        'id' => 'int',
        'spot_id' => 'int',
        'user_id' => 'int',
        'ip_addr' => 'string',
        'port' => 'int',
        'user_agent' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    protected $guarded = ['id'];
    public function spot(): BelongsTo
    {
        return $this->belongsTo('App\Models\Spot');
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }
}
