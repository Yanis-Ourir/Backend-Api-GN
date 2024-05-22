<?php

use App\Models\Game;
use App\Models\GameList;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->integer('rating');
            $table->text('description');
            $table->string('game_time')->nullable(true);
            $table->foreignIdFor(User::class)->constrained();
            $table->foreignIdFor(Status::class)->nullable(true)->constrained();
            $table->morphs('reviewable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
