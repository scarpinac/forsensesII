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
        Schema::create('cliente_contatos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id')->constrained('cliente');
            $table->unsignedBigInteger('tipoContato_id')->constrained('padrao_tipo');
            $table->string('telefone', 15);
            $table->string('contato', 100);
            $table->string('email', 50)->nullable();
            $table->string('observacoes', 100)->nullable();
            $table->unsignedBigInteger('situacao_id')->constrained('padrao_tipo');
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('cliente_id', 'idx_cliente');
            $table->index('tipoContato_id', 'idx_tipo');
            $table->index('contato', 'idx_contato');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cliente_contatos');
    }
};
