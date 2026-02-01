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
        Schema::create('menu', function (Blueprint $table) {
            $table->id();
            $table->string('descricao', 25);
            $table->string('icone', 50);
            $table->string('rota', 80);
            $table->foreignId('menuPai_id')->nullable()->constrained('menu');
            $table->foreignId('permissao_id')->nullable()->constrained('permissao');
            $table->foreignId('situacao_id')->constrained('padrao_tipo');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu');
    }
};
