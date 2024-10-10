<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

class Game extends Model
{
    use HasFactory;

    /**
     * @var float|\Illuminate\Support\HigherOrderCollectionProxy|int|mixed
     */

    protected $fillable = [
        'name',
        'description',
        'editor',
        'rating',
        'slug',
        'release_date',
    ];

    public function image(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function platforms(): BelongsToMany
    {
        return $this->belongsToMany(Platform::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function gameList(): BelongsToMany
    {
        return $this->belongsToMany(GameList::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(Evaluation::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

}
