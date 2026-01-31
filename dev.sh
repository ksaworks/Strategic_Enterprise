#!/bin/bash

# Cores para output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${BLUE}🚀 Inicializando Strategic Enterprise Dev Environment...${NC}"

# Verificar se está no diretório correto
if [ ! -f "artisan" ]; then
    echo -e "${RED}Erro: Execute este script dentro da pasta 'strategic-enterprise'.${NC}"
    exit 1
fi

# ============================================
# 🐳 INICIAR DOCKER (MySQL, Redis, Mailpit)
# ============================================
echo -e "${YELLOW}🐳 Verificando containers Docker...${NC}"

# Verificar se Docker está rodando
if ! docker info > /dev/null 2>&1; then
    echo -e "${RED}Erro: Docker não está rodando. Inicie o Docker primeiro.${NC}"
    exit 1
fi

# Iniciar containers se estiverem parados
MYSQL_CONTAINER="strategic-enterprise-mysql-1"
REDIS_CONTAINER="strategic-enterprise-redis-1"

if [ "$(docker ps -q -f name=$MYSQL_CONTAINER)" ]; then
    echo -e "${GREEN}✓ MySQL já está rodando${NC}"
else
    echo -e "${YELLOW}Iniciando MySQL...${NC}"
    docker start $MYSQL_CONTAINER 2>/dev/null || docker compose up -d mysql 2>/dev/null
    
    # Aguardar MySQL ficar saudável (máximo 30 segundos)
    echo -e "${YELLOW}Aguardando MySQL ficar pronto...${NC}"
    for i in {1..30}; do
        if docker exec $MYSQL_CONTAINER mysqladmin ping -h localhost -u root -ppassword 2>/dev/null | grep -q "alive"; then
            echo -e "${GREEN}✓ MySQL pronto!${NC}"
            break
        fi
        sleep 1
        echo -n "."
    done
    echo ""
fi

# Iniciar Redis (opcional, mas útil para cache)
if [ "$(docker ps -q -f name=$REDIS_CONTAINER)" ]; then
    echo -e "${GREEN}✓ Redis já está rodando${NC}"
else
    echo -e "${YELLOW}Iniciando Redis...${NC}"
    docker start $REDIS_CONTAINER 2>/dev/null || true
fi

# ============================================
# 📦 VERIFICAR DEPENDÊNCIAS
# ============================================
echo -e "${YELLOW}📦 Verificando dependências...${NC}"

if [ ! -d "vendor" ]; then
    echo -e "Instalando dependências PHP (Composer)..."
    composer install
fi

if [ ! -d "node_modules" ]; then
    echo -e "Instalando dependências JS (NPM)..."
    npm install
fi

if [ ! -f ".env" ]; then
    echo -e "${RED}Erro: Arquivo .env não encontrado.${NC}"
    echo -e "Copie o .env.example para .env e configure o banco de dados."
    exit 1
fi

# ============================================
# 🔄 VERIFICAR BANCO DE DADOS
# ============================================
echo -e "${BLUE}🔄 Verificando banco de dados...${NC}"

# Testar conexão com o banco
if php artisan db:show > /dev/null 2>&1; then
    echo -e "${GREEN}✓ Conexão com banco OK${NC}"
else
    echo -e "${YELLOW}⚠ Aguardando banco... (tentando novamente em 3s)${NC}"
    sleep 3
fi

# Rodar migrations pendentes
php artisan migrate --force 2>/dev/null || true

# ============================================
# 🚀 INICIAR SERVIDORES
# ============================================
echo -e "${GREEN}✅ Ambiente pronto!${NC}"
echo -e "${BLUE}🌐 Servidor: http://localhost:8001${NC}"
echo -e "${BLUE}🌐 Vite:     http://localhost:5173${NC}"
echo -e "${YELLOW}📝 Pressione Ctrl+C para encerrar todos os processos.${NC}"
echo ""

# Trap para matar processos filhos ao sair
trap 'echo -e "\n${YELLOW}Encerrando servidores...${NC}"; kill 0' SIGINT SIGTERM

php artisan serve --port=8001 &
npm run dev &

wait
