# AGENTE — Plataforma de RPG

Aplicação Laravel monolítica para gerenciamento de fichas, campanhas, magias, mapas e combate para mesa de RPG com foco inicial em D&D 5e.

## Stack

- Laravel 10
- PostgreSQL 16
- Docker / Docker Compose
- Blade + Livewire + Alpine
- PHPUnit

## Funcionalidades implementadas

- autenticação completa com Breeze
- dashboard do jogador e mestre
- criação de fichas de personagem
- catálogo de magias com filtros por classe e raça
- campanhas e área do mestre
- mapas e cenas de combate
- solicitação de amizade e convites de campanha

## Executando localmente

### Opção 1: ambiente Docker

```bash
docker compose up -d --build
```

A aplicação fica disponível em:

```text
http://localhost:8000
```

O banco PostgreSQL fica em:

```text
localhost:5432
```

Credenciais padrão:

```text
DB_DATABASE=rpg
DB_USERNAME=rpg
DB_PASSWORD=rpgsecret
```

### Opção 2: ambiente local sem Docker

```bash
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## Testes

```bash
DB_CONNECTION=sqlite DB_DATABASE=':memory:' php artisan test
```

## Observações

- A base dos testes foi validada com SQLite em memória para permitir verificação em ambientes sem Docker.
- O ambiente Docker usa PostgreSQL conforme o guia do projeto.
- O comando `docker compose up -d --build` é a forma esperada de subir o projeto completo.
