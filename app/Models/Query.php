<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    protected $casts = [
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
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
