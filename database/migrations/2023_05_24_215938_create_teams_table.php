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
        Schema::create('teams', function (Blueprint $table) {
            $table->id()->comment("Id do time");
            $table->string("name")->comment("Nome do time");
            $table->foreignId('leader_id')->comment("Id do usuário lider de time")->constrained("users");
            $table->integer('active_projects')->comment("Número de projetos ativos");
            $table->integer('worked_projects')->comment("Número de projetos trabalhados");
            $table->integer('tasks_performed')->comment("Número de tasks finalizadas");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
