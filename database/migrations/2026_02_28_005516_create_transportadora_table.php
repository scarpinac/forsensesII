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
        Schema::create('transportadora', function (Blueprint $table) {
            $table->id();
            $table->string('nomeFantasia', 80);
            $table->string('razaoSocial', 80);
            $table->string('cnpj', 14);
            $table->foreignId('situacao_id')->constrained('padrao_tipo');
            $table->text('observacao')->nullable();
            $table->string('estadosAtendidos', 255)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transportadora');
    }
};
