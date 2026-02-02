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
        Schema::create('notificacao', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 50);
            $table->text('mensagem');
            $table->foreignId('tipoNotificacao_id')->constrained('padrao_tipo');
            $table->string('icone', 30)->nullable();
            $table->timestamp('enviar_em');
            $table->boolean('enviado');
            $table->foreignId('enviarNotificacaoPara_id')->nullable()->constrained('padrao_tipo');
            $table->json('enviado_para')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index('enviar_em');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
