#!/bin/bash

# Script Simples de Desenvolvimento
# forSensesII - Laravel + Vite + Monitoramento

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Função para mostrar header
show_header() {
    echo -e "${PURPLE}╔══════════════════════════════════════════════════════╗${NC}"
    echo -e "${PURPLE}║                    🚀 forSensesII Dev Environment       ║${NC}"
    echo -e "${PURPLE}║                Laravel + Vite + Monitoramento          ║${NC}"
    echo -e "${PURPLE}╚════════════════════════════════════════════════════╝${NC}"
    echo ""
}

# Função para mostrar menu
show_menu() {
    echo -e "${CYAN}📋 Escolha uma opção:${NC}"
    echo ""
    echo -e "${BLUE}1)${NC} 🐳 Subir tudo com Docker (Recomendado)"
    echo -e "${BLUE}2)${NC} ⚡ Rodar localmente (php artisan serve + npm run dev)"
    echo -e "${BLUE}3)${NC} 🧹 Parar tudo"
    echo -e "${BLUE}4)${NC} 🚪 Sair"
    echo ""
}

# Função para iniciar Docker
start_docker() {
    echo -e "${YELLOW}🐳 Subindo ambiente completo com Docker...${NC}"
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
        echo -e "${GREEN}✅ Arquivo .env criado${NC}"
    fi
    
    # Iniciar serviços
    echo -e "${YELLOW}🚀 Subindo containers...${NC}"
    docker-compose up -d --build
    
    if [ $? -eq 0 ]; then
        echo ""
        echo -e "${GREEN}🎉 Ambiente iniciado com sucesso!${NC}"
        echo ""
        echo -e "${CYAN}🌐 Serviços disponíveis:${NC}"
        echo -e "   • Aplicação: ${BLUE}http://localhost:8000${NC}"
        echo -e "   • Vite Dev: ${BLUE}http://localhost:5173${NC}"
        echo -e "   • MySQL: ${BLUE}localhost:3307${NC} (root/root123)"
        echo -e "   • Uptime Kuma: ${BLUE}http://localhost:3001${NC}"
        echo -e "   • Grafana: ${BLUE}http://localhost:3000${NC} (admin/admin123)"
        echo -e "   • Prometheus: ${BLUE}http://localhost:9090${NC}"
        echo ""
        echo -e "${YELLOW}💡 Para ver os logs: docker-compose logs -f${NC}"
        echo -e "${YELLOW}💡 Para parar: docker-compose down${NC}"
        echo -e "${YELLOW}💡 Para rodar migrations: docker-compose exec app php artisan migrate${NC}"
        echo -e "${YELLOW}💡 Para reconstruir: docker-compose up --build${NC}"
        
        # Aguardar e perguntar sobre migrations
        sleep 15
        echo ""
        echo -e "${YELLOW}🗄️  Deseja rodar as migrations agora? (s/N)${NC}"
        read -r run_migrations
        if [[ $run_migrations =~ ^[Ss]$ ]]; then
            echo -e "${YELLOW}🗄️  Rodando migrations...${NC}"
            docker-compose exec app php artisan migrate
            echo -e "${GREEN}✅ Migrations concluídas!${NC}"
        fi
    else
        echo -e "${RED}❌ Falha ao iniciar ambiente${NC}"
        return 1
    fi
}

# Função para iniciar localmente
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
        echo -e "${GREEN}✅ Arquivo .env criado${NC}"
    fi
    
    # Instalar dependências
    echo -e "${YELLOW}📦 Instalando dependências...${NC}"
    composer install
    npm install
    
    # Rodar migrations
    echo -e "${YELLOW}🗄️  Rodando migrations...${NC}"
    php artisan migrate
    
    # Iniciar serviços
    echo -e "${YELLOW}🚀 Iniciando serviços...${NC}"
    
    # Verificar se o concurrently está instalado
    if ! command -v concurrently &> /dev/null; then
        echo -e "${YELLOW}📦 Instalando concurrently...${NC}"
        npm install -g concurrently
    fi
    
    # Iniciar com concurrently
    concurrently "php artisan serve --host=0.0.0.0 --port=8000" "npm run dev"
    
    echo ""
    echo -e "${GREEN}🎉 Serviços iniciados!${NC}"
    echo ""
    echo -e "${CYAN}🌐 Aplicação: ${BLUE}http://localhost:8000${NC}"
    echo -e "${CYAN}🔥 Vite: ${BLUE}http://localhost:5173${NC}"
}

# Função para parar tudo
stop_all() {
    echo -e "${YELLOW}🧹 Parando tudo...${NC}"
    echo ""
    
    # Parar containers Docker
    if command -v docker-compose &> /dev/null; then
        echo -e "${YELLOW}🐳 Parando containers Docker...${NC}"
        docker-compose down
        echo -e "${GREEN}✅ Containers Docker parados${NC}"
    fi
    
    # Matar processos locais
    echo -e "${YELLOW}🔍 Verificando processos locais...${NC}"
    pkill -f "php artisan serve" 2>/dev/null
    pkill -f "npm run dev" 2>/dev/null
    pkill -f "node.*vite" 2>/dev/null
    
    echo -e "${GREEN}✅ Processos locais parados${NC}"
    echo ""
    echo -e "${GREEN}✅ Ambiente limpo!${NC}"
}

# Loop principal
main() {
    show_header
    
    while true; do
        show_menu
        read -p "Digite sua escolha (1-4): " choice
        echo ""
        
        case $choice in
            1)
                start_docker
                break
                ;;
            2)
                start_local
                break
                ;;
            3)
                stop_all
                ;;
            4)
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

# Iniciar script
main
