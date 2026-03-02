#!/bin/bash

# Script de Fresh Personalizado para Banco de Dados
# Autor: Sistema ForSenses II
# Data: 2026-02-26

echo "🚀 Iniciando processo de Fresh Personalizado..."

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Função para exibir mensagens
log() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

warn() {
    echo -e "${YELLOW}[WARN]${NC} $1"
}

error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

step() {
    echo -e "${BLUE}[STEP]${NC} $1"
}

# Verificar se está no diretório correto
if [ ! -f "artisan" ]; then
    error "Arquivo artisan não encontrado. Execute este script no diretório raiz do projeto Laravel."
    exit 1
fi

# Passo 1: Limpar banco completamente
step "1/6 - Limpando banco de dados completamente..."
php artisan db:wipe --force
if [ $? -ne 0 ]; then
    error "Falha ao limpar o banco de dados."
    exit 1
fi
log "Banco de dados limpo com sucesso!"

# Passo 2: Rodar migrations até a migration específica
step "2/6 - Rodando migrations iniciais (até 2026_02_17_143000)..."

# Criar arquivo temporário com migrations até o limite
TEMP_MIGRATIONS=$(mktemp)
find database/migrations -name "*.php" | sort | while read migration; do
    filename=$(basename "$migration")
    if [[ "$filename" < "2026_02_17_143000_add_performance_indexes.php" ]] || [[ "$filename" == "2026_02_17_143000_add_performance_indexes.php" ]]; then
        echo "$filename" >> "$TEMP_MIGRATIONS"
    fi
done

# Executar cada migration individualmente
while read migration; do
    echo "   Executando: $migration"
    php artisan migrate --force --path="database/migrations/$migration"
    if [ $? -ne 0 ]; then
        rm -f "$TEMP_MIGRATIONS"
        error "Falha ao executar migration: $migration"
        exit 1
    fi
done < "$TEMP_MIGRATIONS"

rm -f "$TEMP_MIGRATIONS"
log "Migrations iniciais executadas com sucesso!"

# Passo 3: Rodar seeders
step "3/6 - Executando seeders..."
php artisan db:seed --force
if [ $? -ne 0 ]; then
    error "Falha ao executar os seeders."
    exit 1
fi
log "Seeders executados com sucesso!"

# Passo 4: Continuar com as migrations restantes
step "4/6 - Rodando migrations restantes..."
php artisan migrate --force
if [ $? -ne 0 ]; then
    error "Falha ao rodar as migrations restantes."
    exit 1
fi
log "Migrations restantes executadas com sucesso!"

# Passo 5: Otimizar cache
step "5/6 - Otimizando cache da aplicação..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
if [ $? -ne 0 ]; then
    warn "Otimização de cache falhou, mas o processo pode continuar."
else
    log "Cache otimizado com sucesso!"
fi

# Passo 6: Verificar status final
step "6/6 - Verificando status final..."
php artisan migrate:status
echo ""
log "✅ Processo de Fresh Personalizado concluído com sucesso!"
log "🎉 Banco de dados pronto para uso!"

# Informações adicionais
echo ""
echo -e "${BLUE}📋 Resumo do processo:${NC}"
echo "• Banco limpo completamente"
echo "• Migrations até 2026_02_17_143000 executadas"
echo "• Seeders executados"
echo "• Migrations restantes executadas"
echo "• Cache otimizado"
echo ""
echo -e "${GREEN}🚀 Sistema pronto para desenvolvimento!${NC}"
