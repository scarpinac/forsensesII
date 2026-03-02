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
        Schema::create('cliente', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('revenda_id')->nullable()->constrained('revenda');
            $table->unsignedBigInteger('tipoCliente_id')->constrained('padrao_tipo');
            $table->string('nome', 80);
            $table->string('nomeFantasia', 80)->nullable();
            $table->string('nomePreferencia', 80)->nullable();
            $table->date('dataNascimento')->nullable();
            $table->string('rg', 20)->nullable();
            $table->string('rgOrgaoEmissor', 20)->nullable();
            $table->string('cpf', 14)->nullable();
            $table->string('cnpj', 18)->nullable();
            $table->string('inscricaoEstadual', 14)->nullable();
            $table->string('inscricaoMunicipal', 14)->nullable();
            $table->unsignedBigInteger('optanteSimples_id')->nullable()->constrained('padrao_tipo');
            $table->string('responsavelNome', 80)->nullable();
            $table->string('responsavelRg', 20)->nullable();
            $table->string('responsavelRgOrgaoEmissor', 20)->nullable();
            $table->string('responsavelCpf', 14)->nullable();
            $table->string('observacoes', 150)->nullable();
            $table->date('data_cadastro')->nullable();
            $table->decimal('limite_credito', 10, 2)->nullable()->default(0);
            $table->unsignedBigInteger('origem_id')->nullable()->constrained('padrao_tipo');
            $table->string('outra_origem', 50)->nullable();
            $table->unsignedBigInteger('situacao_id')->constrained('padrao_tipo');

            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index('tipoCliente_id', 'idx_tipo_cliente');
            $table->index('cpf', 'idx_cpf');
            $table->index('cnpj', 'idx_cnpj');
            $table->index('nome', 'idx_nome');
            $table->index('nomePreferencia', 'idx_nome_preferencia');
            $table->index('revenda_id', 'idx_revenda');
            $table->index('origem_id', 'idx_origem');
            $table->index('situacao_id', 'idx_situacao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
