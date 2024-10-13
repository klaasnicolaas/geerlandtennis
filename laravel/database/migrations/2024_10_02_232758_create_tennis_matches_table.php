<?php

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
        Schema::create('tennis_matches', function (Blueprint $table): void {
            $table->id();

            // Optional tournament relationship, set to null if tournament is deleted
            $table->foreignId('tournament_id')->nullable()->constrained('tournaments')->onDelete('set null');

            // Teams participating in the match
            $table->foreignId('team_one_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('team_two_id')->constrained('teams')->onDelete('cascade');

            // Winner team, set to null if team is deleted
            $table->foreignId('winner_team_id')->nullable()->constrained('teams')->onDelete('set null');

            // Match-specific data
            $table->date('match_date');
            $table->enum('match_type', MatchType::toArray());
            $table->boolean('is_practice')->default(false);
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
