<?php

use App\Enums\MatchCategory;
use App\Enums\MatchType;
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
        Schema::create('tennis_matches', function (Blueprint $table) {
            $table->id();
            $table->enum('match_type', MatchType::toArray());
            $table->enum('match_category', MatchCategory::toArray());
            $table->foreignId('team_one_player_one_id')->constrained('users');
            $table->foreignId('team_one_player_two_id')->nullable()->constrained('users');
            $table->foreignId('team_two_player_one_id')->constrained('users');
            $table->foreignId('team_two_player_two_id')->nullable()->constrained('users');
            $table->date('match_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tennis_matches');
    }
};
