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
        Schema::create('api', function (Blueprint $table) {
            $table->id();
            $table->foreignId('api_id')->nullable()->constrained('padrao_tipo');
            $table->longText('credencial');
            $table->foreignId('situacao_id')->nullable()->constrained('padrao_tipo');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apis');
    }
};
