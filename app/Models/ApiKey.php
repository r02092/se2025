<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiKey extends Model
{
    protected array $casts = [
        'id' => 'int',
        'user_id' => 'int',
        'name' => 'string',
        'key' => 'string',
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
}
