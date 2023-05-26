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
        Schema::create('users', function (Blueprint $table) {
            $table->id()->comment("ID do usuário");
            $table->foreignId('category_id')->comment("Id da categoria do usuário");
            $table->string('name')->comment("Nome do usuário");
            $table->string('email')->unique()->index()->comment("E-mail do usuário");
            $table->timestamp('email_verified_at')->nullable()->index()->comment("Data em que o usuário foi ativado");
            $table->string('password')->comment("Senha do usuário");
            $table->string('path_image')->comment("Caminho da foto do usuário");
            $table->integer("worked_projects")->comment("Número de projetos trabalhados");
            $table->integer("tasks_performed")->comment("Número de tasks finalizadas");
            $table->boolean("active")->comment("Marca se o usuário está ativado ou não");
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
