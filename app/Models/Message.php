<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'user_id',
        'user_receiver_id',
    ];

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function receiver() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
