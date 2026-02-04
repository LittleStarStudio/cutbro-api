<?php

namespace App\Models;

use App\Traits\BelongsToBarbershop;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use BelongsToBarbershop;
}
