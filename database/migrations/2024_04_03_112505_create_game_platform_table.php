<?php

use App\Models\Game;
use App\Models\Platform;
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
        Schema::create('game_platform', function (Blueprint $table) {
            $table->foreignIdFor(Game::class)->constrained();
            $table->foreignIdFor(Platform::class)->constrained();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_platform');
    }
};
