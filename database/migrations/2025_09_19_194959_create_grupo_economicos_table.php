<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grupo_economicos', function (Blueprint $table) {
            $table->id(); // Isso cria um 'id' BIGINT auto_increment primary key
            $table->string('nome');
            $table->timestamps(); // Isso cria 'created_at' e 'updated_at' do tipo timestamp
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grupo_economicos');
    }
};