<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'document_type',
        'document_number',
    ];

    public function batches(): HasMany
    {
        return $this->hasMany(Batch::class);
    }
}
