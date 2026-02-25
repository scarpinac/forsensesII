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
        Schema::create('gerador_cadastros', function (Blueprint $table) {
            $table->id();
            $table->string('classe', 100);
            $table->foreignId('menuPai_id')->nullable()->constrained('padrao_tipo');;
            $table->boolean('criar_permissoes')->nullable()->default(true);
            $table->boolean('criar_menu')->nullable()->default(true);
            $table->boolean('soft_delete')->nullable()->default(true);
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gerador_cadastros');
    }
};
