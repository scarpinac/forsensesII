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
        Schema::create('notificacao_usuario', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('notificacao_id')->constrained('notificacao');
            $table->boolean('lida')->default(false);
            $table->timestamp('lida_em')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'notificacao_id']);
            $table->index(['user_id', 'lida']);
            $table->index('lida_em');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_reads');
    }
};
