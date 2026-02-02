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
        Schema::create('padrao_tipo_historico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('padrao_tipo_id')->constrained('padrao_tipo');
            $table->json('dados_anteriores')->nullable();
            $table->json('dados_novos')->nullable();
            $table->foreignId('tipoAlteracao_id')->nullable()->constrained('padrao_tipo');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('padrao_tipo_historico');
    }
};
