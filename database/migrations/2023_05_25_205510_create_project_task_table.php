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
        Schema::create('project_task', function (Blueprint $table) {
            $table->id()->comment("Id do relacionamento entre projeto e tarefas");
            $table->foreignId("project_id")->comment("Id do projeto");
            $table->foreignId("task_id")->comment("Id da tarefa");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_task');
    }
};
