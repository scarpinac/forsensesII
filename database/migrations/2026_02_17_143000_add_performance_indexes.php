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
        // Índices para tabelas de histórico (consultas frequentes)
        Schema::table('usuario_historico', function (Blueprint $table) {
            $table->index(['usuario_id', 'created_at'], 'idx_usuario_historico_user_created');
            $table->index('created_at', 'idx_usuario_historico_created');
        });

        Schema::table('menu_historico', function (Blueprint $table) {
            $table->index(['menu_id', 'created_at'], 'idx_menu_historico_menu_created');
            $table->index('created_at', 'idx_menu_historico_created');
        });

        Schema::table('permissao_historico', function (Blueprint $table) {
            $table->index(['permissao_id', 'created_at'], 'idx_permissao_historico_permissao_created');
            $table->index('created_at', 'idx_permissao_historico_created');
        });

        Schema::table('perfil_historico', function (Blueprint $table) {
            $table->index(['perfil_id', 'created_at'], 'idx_perfil_historico_perfil_created');
            $table->index('created_at', 'idx_perfil_historico_created');
        });

        Schema::table('perfil_permissao_historico', function (Blueprint $table) {
            $table->index(['perfil_permissao_id', 'created_at'], 'idx_perfil_permissao_historico_perfil_permissao_created');
            $table->index('created_at', 'idx_perfil_permissao_historico_created');
        });

        Schema::table('perfil_usuario_historico', function (Blueprint $table) {
            $table->index(['perfil_usuario_id', 'created_at'], 'idx_perfil_usuario_historico_perfil_usuario_created');
            $table->index('created_at', 'idx_perfil_usuario_historico_created');
        });

        Schema::table('padrao_historico', function (Blueprint $table) {
            $table->index(['padrao_id', 'created_at'], 'idx_padrao_historico_padrao_created');
            $table->index('created_at', 'idx_padrao_historico_created');
        });

        Schema::table('padrao_tipo_historico', function (Blueprint $table) {
            $table->index(['padrao_tipo_id', 'created_at'], 'idx_padrao_tipo_historico_padrao_tipo_created');
            $table->index('created_at', 'idx_padrao_tipo_historico_created');
        });

        Schema::table('notificacao_historico', function (Blueprint $table) {
            $table->index(['notificacao_id', 'created_at'], 'idx_notificacao_historico_notificacao_created');
            $table->index('created_at', 'idx_notificacao_historico_created');
        });

        Schema::table('api_historico', function (Blueprint $table) {
            $table->index(['api_id', 'created_at'], 'idx_api_historico_api_created');
            $table->index('created_at', 'idx_api_historico_created');
        });

        Schema::table('parametro_historico', function (Blueprint $table) {
            $table->index(['parametro_id', 'created_at'], 'idx_parametro_historico_parametro_created');
            $table->index('created_at', 'idx_parametro_historico_created');
        });

        Schema::table('gerador_cadastros_historico', function (Blueprint $table) {
            $table->index(['gerador_id', 'created_at'], 'idx_gerador_cadastros_historico_gerador_created');
            $table->index('created_at', 'idx_gerador_cadastros_historico_created');
        });

        Schema::table('gerador_cadastros_campos_historico', function (Blueprint $table) {
            $table->index(['campo_id', 'created_at'], 'idx_gerador_cadastros_campos_historico_campo_created');
            $table->index('created_at', 'idx_gerador_cadastros_campos_historico_created');
        });

        // Índices para tabelas de junção (consultas de permissões)
        Schema::table('perfil_usuario', function (Blueprint $table) {
            $table->index(['user_id', 'perfil_id'], 'idx_perfil_usuario_user_perfil');
            $table->index('perfil_id', 'idx_perfil_usuario_perfil');
        });

        Schema::table('perfil_permissao', function (Blueprint $table) {
            $table->index(['perfil_id', 'permissao_id'], 'idx_perfil_permissao_perfil_permissao');
            $table->index('permissao_id', 'idx_perfil_permissao_permissao');
        });

        Schema::table('notificacao_usuario', function (Blueprint $table) {
            $table->index(['notificacao_id', 'user_id'], 'idx_notificacao_usuario_notificacao_user');
            $table->index(['user_id', 'lida'], 'idx_notificacao_usuario_user_lida');
            $table->index('lida_em', 'idx_notificacao_usuario_lida_em');
        });

        // Índices para tabelas principais (consultas frequentes)
        Schema::table('users', function (Blueprint $table) {
            $table->index('email', 'idx_users_email');
            $table->index('admin', 'idx_users_admin');
            $table->index('situacao_id', 'idx_users_situacao');
        });

        Schema::table('menu', function (Blueprint $table) {
            $table->index('menuPai_id', 'idx_menu_menu_pai');
            $table->index('permissao_id', 'idx_menu_permissao');
            $table->index('situacao_id', 'idx_menu_situacao');
        });

        Schema::table('notificacao', function (Blueprint $table) {
            $table->index('tipoNotificacao_id', 'idx_notificacao_tipo');
            $table->index('enviarNotificacaoPara_id', 'idx_notificacao_para');
            $table->index('enviar_em', 'idx_notificacao_enviar_em');
            $table->index('enviado', 'idx_notificacao_enviado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remover índices das tabelas de histórico
        Schema::table('usuario_historico', function (Blueprint $table) {
            $table->dropIndex('idx_usuario_historico_user_created');
            $table->dropIndex('idx_usuario_historico_created');
        });

        Schema::table('menu_historico', function (Blueprint $table) {
            $table->dropIndex('idx_menu_historico_menu_created');
            $table->dropIndex('idx_menu_historico_created');
        });

        Schema::table('permissao_historico', function (Blueprint $table) {
            $table->dropIndex('idx_permissao_historico_permissao_created');
            $table->dropIndex('idx_permissao_historico_created');
        });

        Schema::table('perfil_historico', function (Blueprint $table) {
            $table->dropIndex('idx_perfil_historico_perfil_created');
            $table->dropIndex('idx_perfil_historico_created');
        });

        Schema::table('perfil_permissao_historico', function (Blueprint $table) {
            $table->dropIndex('idx_perfil_permissao_historico_perfil_permissao_created');
            $table->dropIndex('idx_perfil_permissao_historico_created');
        });

        Schema::table('perfil_usuario_historico', function (Blueprint $table) {
            $table->dropIndex('idx_perfil_usuario_historico_perfil_usuario_created');
            $table->dropIndex('idx_perfil_usuario_historico_created');
        });

        Schema::table('padrao_historico', function (Blueprint $table) {
            $table->dropIndex('idx_padrao_historico_padrao_created');
            $table->dropIndex('idx_padrao_historico_created');
        });

        Schema::table('padrao_tipo_historico', function (Blueprint $table) {
            $table->dropIndex('idx_padrao_tipo_historico_padrao_tipo_created');
            $table->dropIndex('idx_padrao_tipo_historico_created');
        });

        Schema::table('notificacao_historico', function (Blueprint $table) {
            $table->dropIndex('idx_notificacao_historico_notificacao_created');
            $table->dropIndex('idx_notificacao_historico_created');
        });

        Schema::table('api_historico', function (Blueprint $table) {
            $table->dropIndex('idx_api_historico_api_created');
            $table->dropIndex('idx_api_historico_created');
        });

        Schema::table('parametro_historico', function (Blueprint $table) {
            $table->dropIndex('idx_parametro_historico_parametro_created');
            $table->dropIndex('idx_parametro_historico_created');
        });

        Schema::table('gerador_cadastros_historico', function (Blueprint $table) {
            $table->dropIndex('idx_gerador_cadastros_historico_gerador_created');
            $table->dropIndex('idx_gerador_cadastros_historico_created');
        });

        Schema::table('gerador_cadastros_campos_historico', function (Blueprint $table) {
            $table->dropIndex('idx_gerador_cadastros_campos_historico_campo_created');
            $table->dropIndex('idx_gerador_cadastros_campos_historico_created');
        });

        // Remover índices das tabelas de junção
        Schema::table('perfil_usuario', function (Blueprint $table) {
            $table->dropIndex('idx_perfil_usuario_user_perfil');
            $table->dropIndex('idx_perfil_usuario_perfil');
        });

        Schema::table('perfil_permissao', function (Blueprint $table) {
            $table->dropIndex('idx_perfil_permissao_perfil_permissao');
            $table->dropIndex('idx_perfil_permissao_permissao');
        });

        Schema::table('notificacao_usuario', function (Blueprint $table) {
            $table->dropIndex('idx_notificacao_usuario_notificacao_user');
            $table->dropIndex('idx_notificacao_usuario_user_lida');
            $table->dropIndex('idx_notificacao_usuario_lida_em');
        });

        // Remover índices das tabelas principais
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_email');
            $table->dropIndex('idx_users_admin');
            $table->dropIndex('idx_users_situacao');
        });

        Schema::table('menu', function (Blueprint $table) {
            $table->dropIndex('idx_menu_menu_pai');
            $table->dropIndex('idx_menu_permissao');
            $table->dropIndex('idx_menu_situacao');
        });

        Schema::table('notificacao', function (Blueprint $table) {
            $table->dropIndex('idx_notificacao_tipo');
            $table->dropIndex('idx_notificacao_para');
            $table->dropIndex('idx_notificacao_enviar_em');
            $table->dropIndex('idx_notificacao_enviado');
        });
    }
};
