# 🚀 Strategic Enterprise

> **Sistema de Gestão de Projetos Corporativos**

---

## 📋 INFORMAÇÕES DO PROJETO

| Item | Valor |
|------|-------|
| **Nome** | Strategic Enterprise |
| **Versão** | 1.0.0 (em desenvolvimento) |
| **Cliente** | AFB Consulting |
| **Desenvolvedor** | KSA Systems |
| **Data Início** | 25/01/2026 |

---

## 🎨 IDENTIDADE VISUAL

| Cor | HEX | Uso |
|-----|-----|-----|
| **Navy** | `#0f1729` | Primária, backgrounds |
| **Gold** | `#d58f05` | Destaque, CTAs |
| **White** | `#ffffff` | Textos, fundos claros |

---

## 🛠️ STACK TECNOLÓGICA OFICIAL

> ⚠️ **IMPORTANTE:** Estas são as versões OFICIAIS aprovadas para o projeto.
> Qualquer alteração deve ser documentada.

### Backend

| Tecnologia | Versão | Status |
|------------|--------|--------|
| **PHP** | 8.4 | ✅ Obrigatório |
| **Laravel** | 12 | ✅ Obrigatório |
| **Livewire** | 4 | ✅ Obrigatório |

### Frontend/Admin

| Tecnologia | Versão | Status |
|------------|--------|--------|
| **FilamentPHP** | 5 | ✅ Obrigatório |
| **TailwindCSS** | 4.x | ✅ Incluído |
| **Alpine.js** | 3.x | ✅ Incluído |

### Banco de Dados

| Tecnologia | Versão | Status |
|------------|--------|--------|
| **MySQL** | 8.4 LTS | ✅ Obrigatório |
| **Redis** | 7.4 | ✅ Obrigatório |

---

## 📁 ESTRUTURA DO REPOSITÓRIO

```
/strategic/
├── strategic-enterprise/     ← 🆕 PROJETO NOVO (Laravel 12 + FilamentPHP 5)
│   ├── app/
│   ├── config/
│   ├── database/
│   ├── public/
│   ├── resources/
│   ├── routes/
│   ├── storage/
│   ├── tests/
│   └── ...
│
└── strategic/
    ├── strategic-legacy/     ← 📦 SISTEMA LEGADO (apenas referência)
    │   ├── modulos/          # Módulos PHP antigos
    │   ├── incluir/          # Includes globais
    │   ├── instalacao/       # Scripts SQL (857 tabelas)
    │   ├── config.php        # Configuração banco legado
    │   └── docs/             ← 📚 DOCUMENTAÇÃO COMPARTILHADA
    │       ├── CHANGELOG.md
    │       ├── diario_desenvolvimento_2026-01-25.md
    │       └── ...
    │
    └── strategic-enterprise/ # (pasta vazia, projeto movido para raiz)
```

> **Nota:** O projeto `strategic-enterprise` foi movido para a **raiz** da pasta `strategic/` para melhor organização.

---

## 🔗 DOCUMENTAÇÃO DE REFERÊNCIA

### Para entender o projeto:

1. **[Stack Tecnológica](docs/stack_tecnologica_oficial_2026-01-25.md)**
   - Versões oficiais de todas as tecnologias
   - Justificativas técnicas das escolhas
   - Requisitos do servidor

2. **[Guia de Identidade Visual](docs/guia_identidade_visual_2026-01-25.md)**
   - Cores, tipografia, layout
   - Configurações do FilamentPHP

3. **[Análise de Engenharia Reversa](docs/analise_engenharia_reversa_2026-01-25.md)**
   - Mapeamento do sistema legado
   - Estrutura das 857 tabelas
   - Regras de negócio identificadas

4. **[Escopo Funcional](docs/ANEXO_I_ESCOPO_FUNCIONAL.md)**
   - Requisitos do sistema
   - Funcionalidades esperadas

5. **[Diário de Desenvolvimento](docs/diario_desenvolvimento_2026-01-25.md)**
   - Registro diário de atividades
   - Problemas encontrados e soluções

6. **[Design System Guide](docs/DESIGN_SYSTEM_GUIDE.md)**
   - Padrões de UI/UX
   - Hierarquia de botões
   - Configurações de tabelas e formulários

---

## 🤖 INSTRUÇÕES PARA OUTRAS IAs

> Se você é uma IA (ChatGPT, Claude, Gemini, Copilot, etc.) trabalhando neste projeto:

### Contexto do Projeto

1. Este é um projeto de **reconstrução** de um sistema legado chamado GPWeb
2. O sistema novo se chama **Strategic Enterprise**
3. O cliente é **AFB Consulting**
4. O desenvolvedor é **KSA Systems** (Kelvin Santos Andrade)

### Regras Obrigatórias

1. **SEMPRE use as versões especificadas na stack**
2. **NUNCA modifique o sistema legado** (pasta `strategic-legacy/`)
3. **SEMPRE documente alterações** no diário de desenvolvimento
4. **SEMPRE siga as cores** da identidade visual

### Stack (não alterar):

```
PHP 8.4 + Laravel 12 + FilamentPHP 5 + Livewire 4
MySQL 8.4 LTS + Redis 7.4
```

### Cores (não alterar):

```
Navy: #0f1729
Gold: #d58f05
White: #ffffff
```

### Rodapé padrão:

```
Strategic Enterprise © 2026 - AFB Consulting
Desenvolvido por KSA Systems
```

---

### Módulos Implementados

| Módulo | Descrição | Status |
|--------|-----------|--------|
| **Core Admin** | Usuários, Roles, Permissions, Shield | ✅ Completo |
| **Companies** | Gestão de empresas, matrizes e filiais | ✅ Completo |
| **Departments** | Estrutura organizacional e departamentos | ✅ Completo |
| **Users** | Gestão de usuários e permissões de acesso | ✅ Completo |
| **Contacts** | Agenda global de contatos (PF/PJ) | ✅ Completo |
| **Projects** | Gestão de projetos e portfólio | ✅ Completo |
| **Tasks** | Gestão de tarefas e cronograma | ✅ Completo |
| **Events** | Calendário e gestão de eventos | ✅ Completo |
| **Files** | Gestão de arquivos e documentos | ✅ Completo |
| **Comments** | Sistema de comentários e timeline | ✅ Completo |
| **Links** | Gestão de links úteis | ✅ Completo |
| **Resources** | Gestão de recursos (salas/equipamentos) | ✅ Completo |
| **Bookings** | Reservas e alocação de recursos | ✅ Completo |
| **Data Migration** | Ferramenta de importação do GPWeb | ✅ Parcial (857 tabelas importadas) |

---

## 📊 STATUS E PROGRESSO

> **Progresso Geral do Projeto:** ~75% Concluído
> **Comparativo com Legado:** 95% do Escopo Core Reimplementado

### O que já foi feito?
- **Fundação:** Estrutura Laravel 12 + Filament 5 + Banco de dados (Done)
- **Core Modules:** Todos os 12 módulos principais do escopo (Projects, Tasks, etc) foram criados (Done)
- **Segurança:** RBAC (Shield) e Autenticação robusta implementados (Done)
- **Interface:** Design System e Dashboard executivo operacionais (Done)

### O que falta implementar?
1. **Módulo de Relatórios:**
   - Builder de relatórios customizáveis
   - Exportação PDF/Excel
2. **Refinamentos Finais:**
   - Integração fina de notificações e e-mails
   - Widgets avançados (Gantt, Kanban visual refinado)
3. **Qualidade e Deploy:**
   - Testes automatizados (Unit/Feature/Browser)
   - Setup de ambiente de produção e CI/CD final
   - Validação de migração de dados em massa

---

## 🎨 UI/UX DESIGN SYSTEM (Novo)

O sistema segue rigorosamente o padrão "Strategic Enterprise" para garantir consistência e densidade de informação.

### 1. Identidade Visual
- **Cor Primária (Gold):** `#d58f05` (Ações principais, foco)
- **Base (Navy):** `#0f1729` (Sidebar, textos fortes)
- **Tipografia:** `Instrument Sans` (Google Fonts)

### 2. Padrão de Botões
Hierarquia obrigatória em todos os formulários (`Create`, `Edit`):

| Ação | Estilo visual | Implementação Filament |
| :--- | :--- | :--- |
| **Salvar / Criar** | Sólido Gold + Sombra | `->color('primary')` |
| **Salvar e Novo** | Outline Cinza + Ícone | `->color('gray')->outlined()->icon(...)` |
| **Cancelar** | Link Cinza | `->color('gray')->link()` |

### 3. Padrão de Tabelas
Maximizar densidade de dados para gestão corporativa:
- **Paginação:** Padrão **25 registros** por página.
- **Estilo:** Linhas compactas (`py-2.5`) e texto reduzido (`text-sm`).

### 4. Formulários
- **Ícones nos Inputs:** Obrigatório uso de `prefixIcon` em campos de texto/select.
- **Layout:** Uso de Grid (2 ou 3 colunas) e Sections para agrupar dados logicamente.

---

## 🚀 COMEÇANDO (Docker / WSL 2)

O projeto migrou para uma infraestrutura containerizada para garantir máxima performance e paridade com produção.

### 📌 Pré-requisitos
1. **WSL 2** com Ubuntu instalado.
2. **Docker Desktop** (com integração WSL ativada para o Ubuntu).
3. VS Code com extensão **WSL** ou **Dev Containers**.

### 🐳 Instalação e Execução
*Todos os comandos devem ser rodados dentro do terminal do Linux (Ubuntu).*

```bash
# 1. Clonar o repositório (dentro do Linux, ex: ~/code)
git clone https://github.com/ksaworks/strategic_Premium strategic-enterprise
cd strategic-enterprise

# 2. Configurar ambiente
cp .env.example .env

# 3. Subir os containers (App, MySQL 8.4, Redis, Mailpit)
./vendor/bin/sail up -d

# 4. Instalar dependências e migrar banco
./vendor/bin/sail composer install
./vendor/bin/sail npm install
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
./vendor/bin/sail npm run dev
```

### 🔑 Acesso ao Sistema
- **URL:** [http://localhost](http://localhost)
- **Admin:** [http://localhost/admin](http://localhost/admin)
- **Email:** `test@example.com` (ou `admin@strategic.com`)
- **Senha:** `password`

### 🛠️ Comandos Úteis (Cheat Sheet)
Como rodamos em Docker, usamos o prefixo `./vendor/bin/sail` (ou `sail` se tiver alias):

| Ação | Comando |
| :--- | :--- |
| **Parar Servidor** | `./vendor/bin/sail prevent` (ou `stop`) |
| **Artisan** | `./vendor/bin/sail artisan <comando>` |
| **Composer** | `./vendor/bin/sail composer <comando>` |
| **Banco de Dados** | Porta **3307** (Host: 127.0.0.1, User: sail, Pass: password) |
| **Emails (Mailpit)** | [http://localhost:8025](http://localhost:8025) |

### ⚡ Por que Docker?
- **Performance:** PHP roda em modo multi-thread (workers) evitando travamentos.
- **Isolamento:** Redis, MySQL e PHP nas versões exatas de produção.
- **Velocidade:** Filesystem do Linux é 10x mais rápido que o NTFS para projetos com muitos arquivos.

---

## 📞 CONTATO

- **Desenvolvedor:** Kelvin Santos Andrade
- **Empresa:** KSA Systems
- **Cliente:** AFB Consulting

---

```
Strategic Enterprise © 2026 - AFB Consulting
Desenvolvido por KSA Systems
```


https://www.sistemagpweb.com.br/forum/thread-15.html

https://www.sistemagpweb.com.br/forum/thread-91.html
