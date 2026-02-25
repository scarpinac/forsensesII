#!/bin/bash

# Script de Desenvolvimento Completo
# forSensesII - Laravel + Vite + Monitoramento

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Função para verificar se comando foi executado com sucesso
check_success() {
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✅ $1${NC}"
    else
        echo -e "${RED}❌ $1${NC}"
        return 1
    fi
}

# Função para mostrar header
show_header() {
    echo -e "${PURPLE}╔════════════════════════════════════════════════════════╗${NC}"
    echo -e "${PURPLE}║                    🚀 forSensesII Dev Environment                 ║${NC}"
    echo -e "${PURPLE}║                Laravel + Vite + Monitoramento                    ║${NC}"
    echo -e "${PURPLE}╚════════════════════════════════════════════════════╝${NC}"
    echo ""
}

# Função para mostrar menu
show_menu() {
    echo -e "${CYAN}📋 Escolha uma opção:${NC}"
    echo ""
    echo -e "${BLUE}1)${NC} 🐳 Iniciar tudo com Docker (Recomendado)"
    echo -e "${BLUE}2)${NC} ⚡ Iniciar apenas Laravel + Vite (Local)"
    echo -e "${BLUE}3)${NC} 📊 Iniciar apenas Monitoramento"
    echo -e "${BLUE}4)${NC} 🛠️  Configurar ambiente"
    echo -e "${BLUE}5)${NC} 🧹 Limpar ambiente"
    echo -e "${BLUE}6)${NC} 🚀 Iniciar Docker Completo (Apache + Vite + Monitoramento)"
    echo -e "${BLUE}7)${NC} 🚪 Sair"
    echo ""
}

# Função para iniciar tudo com Docker
start_docker_all() {
    echo -e "${YELLOW}🐳 Iniciando ambiente completo com Docker...${NC}"
    echo ""
    
    # Verificar Docker
    if ! command -v docker &> /dev/null; then
        echo -e "${RED}❌ Docker não encontrado. Por favor, instale o Docker primeiro.${NC}"
        return 1
    fi
    
    if ! command -v docker-compose &> /dev/null; then
        echo -e "${RED}❌ Docker Compose não encontrado. Por favor, instale o Docker Compose primeiro.${NC}"
        return 1
    fi
    
    # Criar .env se não existir
    if [ ! -f .env ]; then
        echo -e "${YELLOW}📝 Criando arquivo .env...${NC}"
        cp .env.example .env
        check_success "Arquivo .env criado"
    fi
    
    # Criar banco SQLite
    if [ ! -f database/database.sqlite ]; then
        echo -e "${YELLOW}📁 Criando banco SQLite...${NC}"
        mkdir -p database
        touch database/database.sqlite
        check_success "Banco SQLite criado"
    fi
    
    # Iniciar serviços
    echo -e "${YELLOW}🚀 Subindo containers...${NC}"
    docker-compose -f docker-compose.dev.yml -f docker-compose.monitoring.yml up -d
    
    if [ $? -eq 0 ]; then
        echo ""
        echo -e "${GREEN}🎉 Ambiente iniciado com sucesso!${NC}"
        echo ""
        echo -e "${CYAN}🌐 Serviços disponíveis:${NC}"
        echo -e "   • Aplicação: ${BLUE}http://localhost:8000${NC}"
        echo -e "   • Uptime Kuma: ${BLUE}http://localhost:3001${NC}"
        echo -e "   • Grafana: ${BLUE}http://localhost:3000${NC} (admin/admin123)"
        echo -e "   • Prometheus: ${BLUE}http://localhost:9090${NC}"
        echo ""
        echo -e "${YELLOW}💡 Para ver os logs: docker-compose -f docker-compose.dev.yml logs -f${NC}"
        echo -e "${YELLOW}💡 Para parar: docker-compose -f docker-compose.dev.yml down${NC}"
    else
        echo -e "${RED}❌ Falha ao iniciar ambiente${NC}"
        return 1
    fi
}

# Função para iniciar Laravel + Vite localmente
start_local() {
    echo -e "${YELLOW}⚡ Iniciando Laravel + Vite localmente...${NC}"
    echo ""
    
    # Verificar Node.js
    if ! command -v node &> /dev/null; then
        echo -e "${RED}❌ Node.js não encontrado. Por favor, instale o Node.js primeiro.${NC}"
        return 1
    fi
    
    # Verificar PHP
    if ! command -v php &> /dev/null; then
        echo -e "${RED}❌ PHP não encontrado. Por favor, instale o PHP primeiro.${NC}"
        return 1
    fi
    
    # Verificar Composer
    if ! command -v composer &> /dev/null; then
        echo -e "${RED}❌ Composer não encontrado. Por favor, instale o Composer primeiro.${NC}"
        return 1
    fi
    
    # Criar .env se não existir
    if [ ! -f .env ]; then
        echo -e "${YELLOW}📝 Criando arquivo .env...${NC}"
        cp .env.example .env
        php artisan key:generate
        check_success "Arquivo .env criado"
    fi
    
    # Criar banco SQLite
    if [ ! -f database/database.sqlite ]; then
        echo -e "${YELLOW}📁 Criando banco SQLite...${NC}"
        mkdir -p database
        touch database/database.sqlite
        check_success "Banco SQLite criado"
    fi
    
    # Instalar dependências
    echo -e "${YELLOW}📦 Instalando dependências...${NC}"
    composer install
    npm install
    
    # Rodar migrations
    echo -e "${YELLOW}🗄️  Rodando migrations...${NC}"
    php artisan migrate
    
    # Iniciar serviços em background
    echo -e "${YELLOW}🚀 Iniciando serviços...${NC}"
    
    # Iniciar Laravel
    php artisan serve --host=0.0.0.0 --port=8000 &
    LARAVEL_PID=$!
    
    # Iniciar Vite
    npm run dev &
    VITE_PID=$!
    
    # Salvar PIDs para poder parar depois
    echo $LARAVEL_PID > .laravel.pid
    echo $VITE_PID > .vite.pid
    
    echo ""
    echo -e "${GREEN}🎉 Serviços iniciados!${NC}"
    echo ""
    echo -e "${CYAN}🌐 Aplicação: ${BLUE}http://localhost:8000${NC}"
    echo -e "${CYAN}🔥 Vite: ${BLUE}http://localhost:5173${NC}"
    echo ""
    echo -e "${YELLOW}💡 Para parar: ./scripts/dev-start.sh (opção 5)${NC}"
    echo -e "${YELLOW}💡 Ou: kill $LARAVEL_PID $VITE_PID${NC}"
    
    # Manter script rodando para mostrar logs
    wait
}

# Função para iniciar apenas monitoramento
start_monitoring() {
    echo -e "${YELLOW}📊 Iniciando apenas monitoramento...${NC}"
    
    # Verificar Docker
    if ! command -v docker &> /dev/null; then
        echo -e "${RED}❌ Docker não encontrado. Por favor, instale o Docker primeiro.${NC}"
        return 1
    fi
    
    # Iniciar monitoramento simples
    echo -e "${YELLOW}🚀 Subindo monitoramento...${NC}"
    docker-compose -f docker-compose.simple.yml up -d
    
    if [ $? -eq 0 ]; then
        echo ""
        echo -e "${GREEN}🎉 Monitoramento iniciado!${NC}"
        echo ""
        echo -e "${CYAN}🌐 Serviços disponíveis:${NC}"
        echo -e "   • Uptime Kuma: ${BLUE}http://localhost:3001${NC}"
        echo -e "   • Grafana: ${BLUE}http://localhost:3000${NC} (admin/admin123)"
        echo -e "   • Prometheus: ${BLUE}http://localhost:9090${NC}"
        echo ""
        echo -e "${YELLOW}💡 Para parar: docker-compose -f docker-compose.simple.yml down${NC}"
    else
        echo -e "${RED}❌ Falha ao iniciar monitoramento${NC}"
        return 1
    fi
}

# Função para configurar ambiente
setup_env() {
    echo -e "${YELLOW}🛠️  Configurando ambiente...${NC}"
    echo ""
    
    # Criar .env
    if [ ! -f .env ]; then
        echo -e "${YELLOW}📝 Criando .env...${NC}"
        cp .env.example .env
        php artisan key:generate
        check_success ".env criado"
    fi
    
    # Criar banco
    if [ ! -f database/database.sqlite ]; then
        echo -e "${YELLOW}📁 Criando banco SQLite...${NC}"
        mkdir -p database
        touch database/database.sqlite
        check_success "Banco SQLite criado"
    fi
    
    # Instalar dependências
    echo -e "${YELLOW}📦 Instalando dependências...${NC}"
    composer install
    npm install
    check_success "Dependências instaladas"
    
    # Rodar migrations
    echo -e "${YELLOW}🗄️  Rodando migrations...${NC}"
    php artisan migrate
    check_success "Migrations rodadas"
    
    # Link storage
    echo -e "${YELLOW}🔗 Criando link storage...${NC}"
    php artisan storage:link
    check_success "Storage link criado"
    
    echo ""
    echo -e "${GREEN}✅ Ambiente configurado!${NC}"
}

# Função para iniciar Docker completo (Apache + Vite + Monitoramento)
start_docker_complete() {
    echo -e "${YELLOW}🚀 Iniciando Docker completo (Apache + Vite + Monitoramento)...${NC}"
    echo ""
    
    # Verificar Docker
    if ! command -v docker &> /dev/null; then
        echo -e "${RED}❌ Docker não encontrado. Por favor, instale o Docker primeiro.${NC}"
        return 1
    fi
    
    if ! command -v docker-compose &> /dev/null; then
        echo -e "${RED}❌ Docker Compose não encontrado. Por favor, instale o Docker Compose primeiro.${NC}"
        return 1
    fi
    
    # Criar .env se não existir
    if [ ! -f .env ]; then
        echo -e "${YELLOW}📝 Criando arquivo .env...${NC}"
        cp .env.example .env
        check_success "Arquivo .env criado"
    fi
    
    # Criar banco SQLite
    if [ ! -f database/database.sqlite ]; then
        echo -e "${YELLOW}📁 Criando banco SQLite...${NC}"
        mkdir -p database
        touch database/database.sqlite
        check_success "Banco SQLite criado"
    fi
    
    # Iniciar serviços
    echo -e "${YELLOW}🚀 Subindo containers completos...${NC}"
    docker-compose -f docker-compose.complete.yml up -d --build
    
    if [ $? -eq 0 ]; then
        echo ""
        echo -e "${GREEN}🎉 Ambiente Docker completo iniciado!${NC}"
        echo ""
        echo -e "${CYAN}🌐 Serviços disponíveis:${NC}"
        echo -e "   • Aplicação: ${BLUE}http://localhost:8000${NC}"
        echo -e "   • Vite Dev Server: ${BLUE}http://localhost:5173${NC}"
        echo -e "   • Uptime Kuma: ${BLUE}http://localhost:3001${NC}"
        echo -e "   • Grafana: ${BLUE}http://localhost:3000${NC} (admin/admin123)"
        echo -e "   • Prometheus: ${BLUE}http://localhost:9090${NC}"
        echo ""
        echo -e "${YELLOW}💡 Para ver os logs: docker-compose -f docker-compose.complete.yml logs -f${NC}"
        echo -e "${YELLOW}💡 Para parar: docker-compose -f docker-compose.complete.yml down${NC}"
        echo -e "${YELLOW}💡 Para reconstruir: docker-compose -f docker-compose.complete.yml up --build${NC}"
    else
        echo -e "${RED}❌ Falha ao iniciar ambiente${NC}"
        return 1
    fi
}

# Função para limpar ambiente
clean_env() {
    echo -e "${YELLOW}🧹 Limpando ambiente...${NC}"
    echo ""
    
    # Parar processos locais
    if [ -f .laravel.pid ]; then
        LARAVEL_PID=$(cat .laravel.pid)
        kill $LARAVEL_PID 2>/dev/null
        rm .laravel.pid
        echo -e "${GREEN}✅ Laravel parado${NC}"
    fi
    
    if [ -f .vite.pid ]; then
        VITE_PID=$(cat .vite.pid)
        kill $VITE_PID 2>/dev/null
        rm .vite.pid
        echo -e "${GREEN}✅ Vite parado${NC}"
    fi
    
    # Parar containers Docker
    if command -v docker-compose &> /dev/null; then
        echo -e "${YELLOW}🐳 Parando containers Docker...${NC}"
        docker-compose -f docker-compose.dev.yml -f docker-compose.monitoring.yml -f docker-compose.complete.yml down 2>/dev/null
        check_success "Containers Docker parados"
    fi
    
    # Limpar caches
    echo -e "${YELLOW}🗑️  Limpando caches...${NC}"
    php artisan cache:clear 2>/dev/null
    php artisan config:clear 2>/dev/null
    php artisan route:clear 2>/dev/null
    php artisan view:clear 2>/dev/null
    
    echo ""
    echo -e "${GREEN}✅ Ambiente limpo!${NC}"
}

# Loop principal
main() {
    show_header
    
    while true; do
        show_menu
        read -p "Digite sua escolha (1-7): " choice
        echo ""
        
        case $choice in
            1)
                start_docker_all
                break
                ;;
            2)
                start_local
                break
                ;;
            3)
                start_monitoring
                break
                ;;
            4)
                setup_env
                ;;
            5)
                clean_env
                break
                ;;
            6)
                start_docker_complete
                break
                ;;
            7)
                echo -e "${YELLOW}👋 Até logo!${NC}"
                exit 0
                ;;
            *)
                echo -e "${RED}❌ Opção inválida. Tente novamente.${NC}"
                echo ""
                ;;
        esac
    done
}

# Trap para limpar processos ao sair
trap clean_env EXIT

# Iniciar script
main
