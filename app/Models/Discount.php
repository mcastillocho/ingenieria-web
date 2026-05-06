<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'code',
        'type_use',
        'type_discount',
        'amount',
        'minimum_amount',
        'maximum_amount',
        'expiration_date',
        'use_limit',
        'type_limit',
    ];

    protected $casts = [
        'type_use' => 'string',
        'type_discount' => 'string',
        'amount' => 'decimal:2',
        'minimum_amount' => 'decimal:2',
        'maximum_amount' => 'decimal:2',
        'expiration_date' => 'datetime',
        'use_limit' => 'integer',
        'type_limit' => 'string',
    ];
}
