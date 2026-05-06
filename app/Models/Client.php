<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'document_type',
        'document_number',
        'name',
        'lastname',
        'email',
        'phone',
    ];

    protected $casts = [
        'document_type' => 'string',
    ];
}
