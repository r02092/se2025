<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Query extends Model
{
    protected string[] $casts = [
        'id' => 'int',
        'user_id' => 'int',
        'query' => 'string',
        'from_spot_id' => 'int',
        'to_spot_id' => 'int',
        'ip_addr' => 'string',
        'port' => 'int',
        'user_agent' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    protected $guarded = ['id'];
    public function user(): BelongsTo
    {
        return $this->belongsTo('App\Models\User');
    }
}
