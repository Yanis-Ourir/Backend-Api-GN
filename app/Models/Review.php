<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'game_list_id',
        'game_id',
        'status_id',
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
