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
        Schema::create('acabamento', function (Blueprint $table) {
            $table->id();
            $table->string('descricao', 50);
            $table->foreignId('tipoAcabamento_id')->constrained('padrao_tipo');
            $table->foreignId('cor_id')->constrained('cor');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('acabamento');
    }
};
