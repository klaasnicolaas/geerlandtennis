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
        // Create the teams table.
        Schema::create('teams', function (Blueprint $table): void {
            $table->id();
            $table->string('team_hash')->unique();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Create the pivot table for the relationship between teams and users.
        Schema::create('team_user', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('team_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('team_user');
        Schema::dropIfExists('teams');
    }
};
