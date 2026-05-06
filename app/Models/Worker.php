<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Worker extends Model
{
    protected $fillable = [
        'name',
        'lastname',
        'document_type',
        'document_number',
        'email',
        'phone',
    ];

    public function credentials(): HasMany
    {
        return $this->hasMany(Credential::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}
