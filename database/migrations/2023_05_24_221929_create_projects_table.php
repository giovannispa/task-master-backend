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
        Schema::create('projects', function (Blueprint $table) {
            $table->id()->comment("Id do projeto");
            $table->foreignId('status_id')->comment("Id do status do projeto");
            $table->foreignId('team_id')->comment("Id do time do projeto");
            $table->string('name')->comment("Nome do projeto");
            $table->text('description')->nullable()->comment("Descrição do projeto");
            $table->date('start_date')->nullable()->comment("Data de início do projeto");
            $table->date('deadline')->nullable()->comment("Prazo de conclusao do projeto");
            $table->date('end_date')->nullable()->comment("Data de conclusão do projeto");
            $table->integer("active_projects")->comment("Número de projetos ativos");
            $table->integer("worked_projects")->comment("Número de projetos trabalhados");
            $table->integer("tasks_performed")->comment("Número de tasks finalizadas");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
