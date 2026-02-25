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
        Schema::create('gerador_cadastros_campos', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->foreignId('gerador_id')->constrained('gerador_cadastros');
            $table->foreignId('tipo_id')->constrained('padrao_tipo');;
            $table->integer('tamanho_maximo')->nullable();
            $table->string('relacionamento', 100)->nullable();
            $table->boolean('obrigatorio')->default(false);
            $table->boolean('unico')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gerador_cadastros_campos');
    }
};
