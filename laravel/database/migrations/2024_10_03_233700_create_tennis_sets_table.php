<?php

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
        Schema::create('tennis_sets', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('tennis_match_id')->constrained('tennis_matches')->onDelete('cascade');
            $table->integer('set_number');
            $table->integer('team_one_score');
            $table->integer('team_two_score');
            $table->boolean('has_tie_break')->default(false);
            $table->integer('team_one_tie_break_score')->nullable();
            $table->integer('team_two_tie_break_score')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sets');
    }
};
