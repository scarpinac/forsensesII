#!/bin/bash

# Script Rápido de Desenvolvimento
# forSensesII - Laravel + Vite + Monitoramento

# Cores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
PURPLE='\033[0;35m'
CYAN='\033[0;36m'
NC='\033[0m' # No Color

# Função para verificar se containers estão rodando
check_containers() {
    echo -e "${YELLOW}🔍 Verificando containers...${NC}"
    
    # Verificar se o container principal está rodando
    if docker ps --format "table {{.Names}}" | grep -q "php_app"; then
        echo -e "${GREEN}✅ Container php_app está rodando${NC}"
        
        # Verificar se as portas estão respondendo
        if curl -s http://localhost:8000 > /dev/null; then
            echo -e "${GREEN}✅ Aplicação respondendo em http://localhost:8000${NC}"
        else
            echo -e "${RED}❌ Aplicação não está respondendo${NC}"
        fi
        
        if curl -s http://localhost:5173 > /dev/null; then
            echo -e "${GREEN}✅ Vite respondendo em http://localhost:5173${NC}"
        else
            echo -e "${RED}❌ Vite não está respondendo${NC}"
        fi
        
        # Verificar monitoramento
        if docker ps --format "table {{.Names}}" | grep -q "uptime-kuma"; then
            echo -e "${GREEN}✅ Uptime Kuma rodando${NC}"
        fi
        
        if docker ps --format "table {{.Names}}" | grep -q "grafana"; then
            echo -e "${GREEN}✅ Grafana rodando${NC}"
        fi
        
        if docker ps --format "table {{.Names}}" | grep -q "prometheus"; then
            echo -e "${GREEN}✅ Prometheus rodando${NC}"
        fi
        
        return 0
    else
        echo -e "${RED}❌ Container php_app não está rodando${NC}"
        return 1
    fi
}

# Função para mostrar header
show_header() {
    echo -e "${PURPLE}╔════════════════════════════════════════════════════════╗${NC}"
    echo -e "${PURPLE}║                 🚀 forSensesII Fast Dev                    ║${NC}"
    echo -e "${PURPLE}║            Laravel + Vite + Monitoramento                ║${NC}"
    echo -e "${PURPLE}╚════════════════════════════════════════════════════════╝${NC}"
    echo ""
}

# Função para mostrar menu
show_menu() {
    echo -e "${CYAN}📋 Escolha uma opção:${NC}"
    echo ""
    echo -e "${BLUE}1)${NC} 🚀 Subir ambiente (build completo)"
    echo -e "${BLUE}2)${NC} 🔍 Verificar status atual"
    echo -e "${BLUE}3)${NC} 🔄 Reiniciar containers"
    echo -e "${BLUE}4)${NC} 🏗️  Reconstruir (só se necessário)"
    echo -e "${BLUE}5)${NC} 🧹 Parar tudo"
    echo -e "${BLUE}6)${NC} 🚪 Sair"
    echo ""
}

# Função para subir com cache
start_fast() {
    echo -e "${YELLOW}🚀 Subindo ambiente...${NC}"
    
    # Criar .env se não existir
    if [ ! -f .env ]; then
        echo -e "${YELLOW}📝 Criando arquivo .env...${NC}"
        cp .env.example .env
        echo -e "${GREEN}✅ Arquivo .env criado${NC}"
    fi
    
    # Subir (sem cache para evitar problemas)
    echo -e "${YELLOW}🏗️  Build inicial (pode demorar um pouco)...${NC}"
    docker-compose up -d --build
    
    if [ $? -eq 0 ]; then
        echo ""
        echo -e "${GREEN}🎉 Ambiente iniciado!${NC}"
        echo ""
        echo -e "${CYAN}🌐 Serviços disponíveis:${NC}"
        echo -e "   • Aplicação: ${BLUE}http://localhost:8000${NC}"
        echo -e "   • Vite Dev: ${BLUE}http://localhost:5173${NC}"
        echo -e "   • MySQL: ${BLUE}localhost:3307${NC} (root/root123)"
        echo -e "   • Uptime Kuma: ${BLUE}http://localhost:3001${NC}"
        echo -e "   • Grafana: ${BLUE}http://localhost:3000${NC} (admin/admin123)"
        echo -e "   • Prometheus: ${BLUE}http://localhost:9090${NC}"
        echo ""
        echo -e "${YELLOW}💡 Para ver logs: docker-compose logs -f${NC}"
        echo -e "${YELLOW}💡 Para rodar migrations: docker-compose exec app php artisan migrate${NC}"
        
        # Aguardar um pouco e verificar
        sleep 20
        check_containers
        
        # Perguntar sobre migrations
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
    fi
}

# Função para reconstruir
rebuild() {
    echo -e "${YELLOW}🏗️  Reconstruindo imagem...${NC}"
    docker-compose build --no-cache
    docker-compose up -d
}

# Função para reiniciar
restart() {
    echo -e "${YELLOW}🔄 Reiniciando containers...${NC}"
    docker-compose restart
    sleep 5
    check_containers
}

# Função para parar
stop_all() {
    echo -e "${YELLOW}🧹 Parando containers...${NC}"
    docker-compose down
    echo -e "${GREEN}✅ Containers parados${NC}"
}

# Loop principal
main() {
    show_header
    
    # Verificar status atual primeiro
    check_containers
    echo ""
    
    while true; do
        show_menu
        read -p "Digite sua escolha (1-6): " choice
        echo ""
        
        case $choice in
            1)
                start_fast
                break
                ;;
            2)
                check_containers
                ;;
            3)
                restart
                ;;
            4)
                rebuild
                ;;
            5)
                stop_all
                ;;
            6)
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
