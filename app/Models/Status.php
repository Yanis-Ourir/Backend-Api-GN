<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Status extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon',
        'color'
    ];

    public function evaluation() : BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }


}
