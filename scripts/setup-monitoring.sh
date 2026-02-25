#!/bin/bash

# Script de Configuração de Monitoramento Gratuito
# forSensesII - Laravel Project

echo "🚀 Configurando monitoramento para forSensesII..."

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Função para verificar se comando foi executado com sucesso
check_success() {
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✅ $1${NC}"
    else
        echo -e "${RED}❌ $1${NC}"
        exit 1
    fi
}

# 1. Verificar Docker
echo "📦 Verificando Docker..."
if ! command -v docker &> /dev/null; then
    echo -e "${RED}❌ Docker não encontrado. Por favor, instale o Docker primeiro.${NC}"
    exit 1
fi
check_success "Docker encontrado"

# 2. Verificar Docker Compose
echo "📦 Verificando Docker Compose..."
if ! command -v docker-compose &> /dev/null; then
    echo -e "${RED}❌ Docker Compose não encontrado. Por favor, instale o Docker Compose primeiro.${NC}"
    exit 1
fi
check_success "Docker Compose encontrado"

# 3. Criar diretório de monitoring
echo "📁 Criando diretório de monitoring..."
mkdir -p monitoring
check_success "Diretório monitoring criado"

# 4. Copiar arquivo de configuração do Prometheus
echo "📝 Configurando Prometheus..."
if [ ! -f "monitoring/prometheus.yml" ]; then
    echo -e "${YELLOW}⚠️  Arquivo prometheus.yml já existe. Pulando...${NC}"
else
    check_success "Prometheus já configurado"
fi

# 5. Iniciar serviços de monitoramento
echo "🚀 Iniciando serviços de monitoramento..."
docker-compose -f docker-compose.monitoring.yml up -d
check_success "Serviços de monitoramento iniciados"

# 6. Aguardar serviços iniciarem
echo "⏳ Aguardando serviços iniciarem..."
sleep 10

# 7. Verificar status dos serviços
echo "🔍 Verificando status dos serviços..."

# Verificar Uptime Kuma
if curl -f http://localhost:3001 > /dev/null 2>&1; then
    echo -e "${GREEN}✅ Uptime Kuma está rodando em http://localhost:3001${NC}"
else
    echo -e "${RED}❌ Uptime Kuma não está respondendo${NC}"
fi

# Verificar Grafana
if curl -f http://localhost:3000 > /dev/null 2>&1; then
    echo -e "${GREEN}✅ Grafana está rodando em http://localhost:3000 (admin/admin123)${NC}"
else
    echo -e "${RED}❌ Grafana não está respondendo${NC}"
fi

# Verificar Prometheus
if curl -f http://localhost:9090 > /dev/null 2>&1; then
    echo -e "${GREEN}✅ Prometheus está rodando em http://localhost:9090${NC}"
else
    echo -e "${RED}❌ Prometheus não está respondendo${NC}"
fi

# 8. Configurar variáveis de ambiente
echo "⚙️  Configurando variáveis de ambiente..."
if ! grep -q "METRICS_TOKEN" .env; then
    echo "" >> .env
    echo "# Monitoramento" >> .env
    echo "METRICS_TOKEN=$(openssl rand -hex 16)" >> .env
    echo "APP_VERSION=1.0.0" >> .env
    check_success "Variáveis de monitoramento adicionadas ao .env"
else
    echo -e "${YELLOW}⚠️  Variáveis de monitoramento já existem no .env${NC}"
fi

# 9. Testar health check da aplicação
echo "🏥 Testando health check da aplicação..."
if curl -f http://localhost:8000/health > /dev/null 2>&1; then
    echo -e "${GREEN}✅ Health check da aplicação está funcionando${NC}"
else
    echo -e "${YELLOW}⚠️  Health check não respondeu. Certifique-se que a aplicação está rodando em http://localhost:8000${NC}"
fi

# 10. Exibir instruções finais
echo ""
echo "🎉 Configuração de monitoramento concluída!"
echo ""
echo "📊 **Painéis de Monitoramento:**"
echo "   • Uptime Kuma: http://localhost:3001"
echo "   • Grafana: http://localhost:3000 (admin/admin123)"
echo "   • Prometheus: http://localhost:9090"
echo ""
echo "🏥 **Health Checks:**"
echo "   • Health Check: http://localhost:8000/health"
echo "   • Métricas: http://localhost:8000/metrics?token=TOKEN_DO_ENV"
echo ""
echo "📝 **Próximos Passos:**"
echo "   1. Acesse o Uptime Kuma e adicione monitors para seu site"
echo "   2. Importe o dashboard do Laravel no Grafana"
echo "   3. Configure alertas no Grafana"
echo "   4. (Opcional) Configure Sentry para error tracking"
echo ""
echo "📚 **Documentação:**"
echo "   • docs/monitoramento-gratuito.md"
echo ""

# 11. Sugestão de configuração do Sentry
echo "🐛 **Para configurar Sentry (opcional):**"
echo "   composer require sentry/sentry-laravel"
echo "   # Adicionar SENTRY_LARAVEL_DSN no .env"
echo ""

echo "✨ Monitoramento configurado com sucesso!"
