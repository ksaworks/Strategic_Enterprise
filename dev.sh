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

# Verificar dependências
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

# Limpar cache (opcional, bom para dev)
# php artisan optimize:clear

# Rodar migrations (apenas se necessário, evita hooks interativos chatos as vezes, mas é bom avisar)
echo -e "${BLUE}🔄 Verificando banco de dados...${NC}"
# php artisan migrate --force

echo -e "${GREEN}✅ Ambiente pronto!${NC}"
echo -e "${BLUE}🌐 Servidor: http://localhost:8001${NC}"
echo -e "${YELLOW}📝 Pressione Ctrl+C para encerrar todos os processos.${NC}"

# Executar comandos em paralelo usando o próprio Laravel Composer script ou concurrently se disponível
# Mas vamos usar o método nativo do bash para garantir compatibilidade
# Trap para matar processos filhos ao sair
trap 'kill 0' SIGINT

php artisan serve --port=8001 &
npm run dev &

wait
