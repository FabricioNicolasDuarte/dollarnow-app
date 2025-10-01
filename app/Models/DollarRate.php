<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DollarRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'buy_price',
        'sell_price',
        'rate_date',
    ];

    protected $casts = [
        'rate_date' => 'date',
        'buy_price' => 'decimal:2',
        'sell_price' => 'decimal:2',
    ];
}