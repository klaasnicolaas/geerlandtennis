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
        Schema::create('sets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tennis_match_id')->constrained('tennis_matches')->onDelete('cascade');
            $table->integer('set_number');
            $table->integer('team_one_score');
            $table->integer('team_two_score');
            $table->boolean('tie_break')->default(false);
            $table->enum('winning_team', ['team_one', 'team_two']);
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
