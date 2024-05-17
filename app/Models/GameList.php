<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class GameList extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'description',
        'is_private',
        'user_id',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function dislikes(): MorphMany
    {
        return $this->morphMany(Dislike::class, 'dislikeable');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function games(): BelongsToMany
    {
        return $this->belongsToMany(Game::class);
    }
}
