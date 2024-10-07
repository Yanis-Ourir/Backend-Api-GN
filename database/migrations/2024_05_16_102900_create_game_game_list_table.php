<?php

use App\Models\Game;
use App\Models\GameList;
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
        Schema::create('game_game_list', function (Blueprint $table) {
            $table->foreignIdFor(GameList::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(Game::class)->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_list');
    }
};
