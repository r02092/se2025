<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    protected $casts = [
        'id' => 'int',
        'spot_id' => 'int',
        'keyword' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];
    protected $guarded = ['id'];
    public function spot()
    {
        return $this->belongsTo(Spot::class);
    }
}
