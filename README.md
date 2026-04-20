# 🚗 Garagem — Cadastro de Carros com Login

Sistema web completo de gerenciamento de carros com autenticação de usuários, construído com **PHP 8.2 + MySQL 8**, orquestrado com **Docker Compose**.

```
┌──────────────────────────────────────────────────────┐
│                  Docker Network                       │
│                                                       │
│   ┌──────────────────┐       ┌──────────────────┐    │
│   │  Container: APP  │  →    │  Container: DB   │    │
│   │  PHP 8.2 + Apache│       │  MySQL 8.4       │    │
│   │  Porta: 8080     │       │  Porta: 3306     │    │
│   └──────────────────┘       └──────────────────┘    │
│           ↑                                           │
└───────────┼───────────────────────────────────────────┘
            │
         Navegador
      localhost:8080
```

---

## 📋 Pré-requisitos

| Ferramenta     | Versão mínima | Verificação                  |
|----------------|---------------|------------------------------|
| Docker         | 24+           | `docker --version`           |
| Docker Compose | 2.20+         | `docker compose version`     |

---

## 🚀 Deploy — Passo a Passo

### 1. Clone o repositório

```bash
git clone https://github.com/<seu-usuario>/<seu-repo>.git
cd <seu-repo>
```

### 2. Suba os containers

```bash
docker compose up -d
```

O Docker irá:
1. Baixar as imagens (`php:8.2-apache`, `mysql:8.4.4`)
2. Construir a imagem da aplicação PHP
3. Inicializar o banco com o schema e um usuário de teste
4. Aguardar o MySQL ficar saudável antes de subir o PHP
5. Expor a aplicação em `http://localhost:8080`

### 3. Acesse no navegador

```
http://localhost:8080
```

Você será redirecionado para a tela de login.

### 4. Conta de teste

| Campo | Valor              |
|-------|--------------------|
| Email | admin@email.com    |
| Senha | 123456             |

---

## 📁 Estrutura do Projeto

```
.
├── docker-compose.yml          # Orquestração dos containers
├── Dockerfile                  # Build da imagem PHP+Apache
├── sql/
│   └── init.sql                # Schema do banco + dados iniciais
└── src/                        # Código-fonte da aplicação
    ├── style.css               # Estilos globais
    ├── index.php               # Lista de carros (página principal)
    ├── login.php               # Tela de login
    ├── cadastro_usuario.php    # Cadastro de novo usuário
    ├── logout.php              # Encerrar sessão
    ├── includes/
    │   ├── db.php              # Conexão com o banco
    │   └── auth.php            # Funções de autenticação/sessão
    └── carros/
        ├── novo.php            # Formulário de cadastro de carro
        ├── editar.php          # Formulário de edição de carro
        └── excluir.php         # Remoção de carro
```

---

## ✨ Funcionalidades

- **Login e logout** com sessão PHP segura
- **Cadastro de usuário** com senha criptografada (`password_hash`)
- **CRUD de carros** por usuário autenticado:
  - Listar todos os carros do usuário
  - Cadastrar novo carro (marca, modelo, ano, cor, placa, preço)
  - Editar carro existente
  - Excluir carro com confirmação
- Cada usuário vê e gerencia **apenas seus próprios carros**
- Validação de formulários no servidor

---

## 🛠️ Tecnologias

| Camada        | Tecnologia         |
|---------------|--------------------|
| Linguagem     | PHP 8.2            |
| Servidor web  | Apache             |
| Banco de dados| MySQL 8.4          |
| Containerização | Docker Compose   |

---

## 🔧 Comandos Úteis

```bash
# Ver status dos containers
docker compose ps

# Ver logs em tempo real
docker compose logs -f

# Acessar o MySQL direto
docker exec -it mysql_db mysql -umeu_usuario -pminha_senha carros_db

# Ver tabela de carros
docker exec -it mysql_db mysql -umeu_usuario -pminha_senha carros_db -e "SELECT * FROM carros;"

# Reconstruir após mudanças no código
docker compose up -d --build

# Parar e remover containers (mantém dados)
docker compose down

# Parar, remover containers E apagar dados do banco
docker compose down -v
```

---

## 🔒 Segurança

- Senhas armazenadas com `password_hash()` (bcrypt)
- Verificação com `password_verify()`
- Proteção contra SQL Injection via `prepared statements`
- Saídas escapadas com `htmlspecialchars()`
- Cada operação valida que o carro pertence ao usuário logado
