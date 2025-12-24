<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $casts = [
        'spot_id' => 'int',
        'user_id' => 'int',
        'rate' => 'int',
        'comment' => 'string',
        'views' => 'int',
        'ip_addr' => 'string',
        'port' => 'int',
        'user_agent' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime'
    ];
    protected $guarded = ['id'];
    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
