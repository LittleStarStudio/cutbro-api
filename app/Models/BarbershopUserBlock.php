<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BarbershopUserBlock extends Model
{
    protected $fillable = [
        'barbershop_id',
        'user_id',
        'status',
        'reason'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}