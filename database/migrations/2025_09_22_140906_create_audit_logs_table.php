<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action'); // CREATE, UPDATE, DELETE
            $table->string('table_name'); // Nome da tabela afetada
            $table->unsignedBigInteger('record_id'); // ID do registro afetado
            $table->json('old_values')->nullable(); // Valores antigos
            $table->json('new_values')->nullable(); // Valores novos
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); // Usuário que fez a ação
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['table_name', 'record_id']);
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_logs');
    }
};