<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
    {
public function up(): void
{
    Schema::create('bandeiras', function (Blueprint $table) {
        $table->id();
        $table->string('nome');
        // A chave estrangeira seguindo a convenção do Laravel:
        $table->foreignId('grupo_economico_id')->constrained('grupo_economicos')->onDelete('cascade');
        $table->timestamps();
    });
}

public function down(): void
    {
        Schema::dropIfExists('bandeiras');
    }
};