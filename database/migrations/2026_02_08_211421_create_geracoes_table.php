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
        Schema::create('geracoes', function (Blueprint $table) {
            $table->id();
            $table->string('classe', 100);
            $table->string('modulo_pai', 50);
            $table->boolean('soft_delete')->default(true);
            $table->boolean('timestamps')->default(true);
            $table->boolean('criar_permissoes')->default(true);
            $table->boolean('criar_menu')->default(true);
            $table->text('observacoes')->nullable();
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
        Schema::dropIfExists('geracoes');
    }
};
