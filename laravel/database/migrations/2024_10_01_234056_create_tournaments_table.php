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
        // Create the tournaments table.
        Schema::create('tournaments', function (Blueprint $table): void {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('tournament_type', MatchType::toArray());
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();
        });

        // Create the pivot table for the relationship between teams and tournaments.
        Schema::create('team_tournament', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('tournament_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_tournament');
        Schema::dropIfExists('tournaments');
    }
};
