<?php

namespace App\Models;

use App\Traits\BelongsToBarbershop;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use BelongsToBarbershop;
}
