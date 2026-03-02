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
        Schema::create('revenda', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipoRevenda_id')->constrained('padrao_tipo');
            $table->string('nomeFantasia', 80);
            $table->string('razaoSocial', 80);
            $table->unsignedBigInteger('matrizRevenda_id')->nullable()->constrained('revenda');
            $table->dateTime('dataCriacao');
            $table->string('cnpj', 14);
            $table->string('inscricaoEstadual', 20);
            $table->string('inscricaoMunicipal', 20);
            $table->unsignedBigInteger('optanteSimples_id')->constrained('padrao_tipo');
            $table->string('responsavelNome', 50)->nullable();
            $table->string('responsavelRg', 15)->nullable();
            $table->string('responsavelRgOrgaoEmissor', 15)->nullable();
            $table->string('responsavelCpf', 11)->nullable();
            $table->text('observacao')->nullable();
            $table->unsignedBigInteger('situacao_id')->constrained('padrao_tipo');
            $table->integer('nivelDesconto')->nullable();
            $table->dateTime('dataUltimaAlteracaoDesconto')->nullable();
            $table->decimal('descontoInicial', 4, 2)->nullable();
            $table->decimal('descontoMaximo', 4, 2)->nullable();
            $table->decimal('descontoAtual', 4, 2)->nullable();
            $table->string('token', 255)->nullable();
            $table->date('dataAberturaRevenda');
            $table->string('ramoAtividade', 100);
            $table->unsignedBigInteger('tipoRegime_id')->constrained('padrao_tipo');
            $table->text('fornecedoresAudio')->nullable();
            $table->text('fornecedoresVideo')->nullable();
            $table->text('fornecedoresAutomacao')->nullable();
            $table->unsignedBigInteger('tipoShowroom_id')->constrained('padrao_tipo');
            $table->decimal('areaExposicao', 7, 2);
            $table->boolean('temJardim');
            $table->boolean('descontoVitalicio');
            $table->dateTime('dataAprovacaoCadastro')->nullable();
            $table->unsignedBigInteger('origemCadastro_id')->constrained('padrao_tipo');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revenda');
    }
};
