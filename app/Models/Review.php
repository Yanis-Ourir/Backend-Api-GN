<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'rating',
        'description',
        'game_time',
        'status_id',
        'game_id',
        'game_list_id',
    ];

    public function gameList(): BelongsTo
    {
        return $this->belongsTo(GameList::class);
    }

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class);
    }

}
