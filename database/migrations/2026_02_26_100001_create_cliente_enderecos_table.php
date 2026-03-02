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
        Schema::create('cliente_endereco', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id')->constrained('cliente');
            $table->unsignedBigInteger('tipoEndereco_id')->constrained('padrao_tipo');
            $table->string('cep', 9);
            $table->string('logradouro', 100);
            $table->string('numero', 10);
            $table->string('complemento', 50)->nullable();
            $table->string('bairro', 50);
            $table->string('cidade', 50);
            $table->char('estado', 2);
            $table->unsignedBigInteger('situacao_id')->constrained('padrao_tipo');
            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('cliente_id', 'idx_cliente');
            $table->index('cep', 'idx_cep');
            $table->index('tipoEndereco_id', 'idx_tipo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cliente_enderecos');
    }
};
