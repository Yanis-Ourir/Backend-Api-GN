<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'rating',
        'description',
        'gameTime',
        'game_id',
        'status_id', // Niveau de complétion du jeu au moment où l'utilisateur publie son évaluation sur ce même jeu (en cours, terminé, etc.)
        'user_id',
    ];

    public function game() : BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function status() : BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'entity');
    }

    public function dislikes(): MorphMany
    {
        return $this->morphMany(Dislike::class, 'entity');
    }
}
