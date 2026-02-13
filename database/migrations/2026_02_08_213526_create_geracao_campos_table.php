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
        Schema::create('geracao_campos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('geracao_id')->constrained('geracoes')->onDelete('cascade');
            $table->string('nome', 100);
            $table->string('tipo', 50); // string, integer, decimal, date, boolean, text, etc.
            $table->integer('tamanho')->nullable();
            $table->integer('decimal_places')->nullable();
            $table->boolean('obrigatorio')->default(false);
            $table->boolean('unique')->default(false);
            $table->string('default_value')->nullable();
            $table->text('observacoes')->nullable();
            $table->integer('ordem')->default(0);
            $table->timestamps();
            
            $table->index(['geracao_id', 'ordem']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('geracao_campos');
    }
};
