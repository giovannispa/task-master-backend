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
        Schema::create('project_team', function (Blueprint $table) {
            $table->id()->comment("Id do registro de relacionamento de projeto e time");
            $table->foreignId('project_id')->comment("Id do projeto");
            $table->foreignId('team_id')->comment("Id do time");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_team');
    }
};
