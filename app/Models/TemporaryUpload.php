<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemporaryUpload extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function move(): void
    {
        $attributes = $this->only(['filename', 'hash', 'uuid']);

        Upload::create($attributes);

        $this->delete();
    }

    public function scopeFor(Builder $query, string $uuid): Builder
    {
        return $query->where('uuid', $uuid);
    }
}
