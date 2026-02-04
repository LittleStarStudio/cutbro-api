<?php

namespace App\Models;

use App\Traits\BelongsToBarbershop;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Barber extends Model
{
    use BelongsToBarbershop, SoftDeletes;
}
