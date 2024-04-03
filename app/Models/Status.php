<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Status extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function evaluation() : BelongsTo
    {
        return $this->belongsTo(Evaluation::class);
    }

    public function review() : BelongsTo
    {
        return $this->belongsTo(Review::class);
    }


}
