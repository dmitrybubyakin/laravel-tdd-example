<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryUpload extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeFor(Builder $query, string $uuid): Builder
    {
        return $query->where('uuid', $uuid);
    }
}
