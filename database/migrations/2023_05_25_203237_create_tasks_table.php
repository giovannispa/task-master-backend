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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id()->comment("Id da tarefa");
            $table->foreignId('project_id')->comment("Id do projeto")->constrained();
            $table->foreignId('status_id')->comment("Status da tarefa")->constrained();
            $table->foreignId('priority_id')->comment("Prioridade da tarefa")->constrained();
            $table->foreignId('created_by')->comment("Id do usuário que criou a tarefa")->constrained('users');
            $table->foreignId('assigned_to')->comment("Id do usuário a quem a tarefa é atribuída")->constrained('users');
            $table->string('name')->comment("Nome da tarefa");
            $table->text('description')->nullable()->comment("Descrição da tarefa");
            $table->date('start_date')->nullable()->comment("Data de início da tarefa");
            $table->date('end_date')->nullable()->comment("Data de conclusão da tarefa");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
