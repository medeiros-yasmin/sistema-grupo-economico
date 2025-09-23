<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('unidades', function (Blueprint $table) {
            // Altera para armazenar apenas números (14 dígitos)
            $table->string('cnpj', 14)->change();
        });
    }

    public function down()
    {
        Schema::table('unidades', function (Blueprint $table) {
            // Reverte se necessário
            $table->string('cnpj', 18)->change();
        });
    }
};