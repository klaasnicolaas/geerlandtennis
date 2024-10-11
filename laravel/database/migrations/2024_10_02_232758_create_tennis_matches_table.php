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
            $table->foreignId('team_one_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('team_two_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('winner_team_id')->nullable()->constrained('teams')->onDelete('set null');
            $table->foreignId('tournament_id')->nullable()->constrained('tournaments')->onDelete('set null');
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
