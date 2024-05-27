## Pré-requisitos

- [Git](https://git-scm.com/)
- [Docker Compose](https://docs.docker.com/compose/)
- [Composer](https://getcomposer.org/)

`Certifique-se de que você tenha todas as dependências acima instaladas antes de prosseguir.`

## Passos

#### Clonar o repositório

```bash
git clone https://github.com/GuilhermeYamasaki/brazil-card-api.git
```

#### Entrar na pasta

```bash
cd brazil-card-api
```

#### Baixar dependencias

```bash
composer install
```

#### Copiar .env 

```bash
cp .env.example .env
```

#### Adicionar alias do Sail

```bash
alias sail='[ -f sail ] && sh sail || sh vendor/bin/sail'
```

#### Construir container

```bash
sail up -d
```

#### Gerar chave criptografada

```bash
sail artisan key:generate
```

#### Criar banco de dados

```bash
sail artisan migrate:fresh --seed
```

#### Rode os testes

```bash
sail artisan test
```

#### Abrir terminal e deixar executando

```bash
sail artisan queue:work
```

## Tecnologias

- [Laravel 11.x](https://laravel.com/)
- [Pint](https://laravel.com/docs/11.x/pint)
- [Redis](https://redis.io/)
- [PostgreSQL](https://www.postgresql.org/)
- [Mailpit](https://mailpit.axllent.org/)
- [Telescope](https://laravel.com/docs/11.x/telescope)
- [Horizon](https://laravel.com/docs/11.x/horizon)
- [Sail](https://laravel.com/docs/11.x/sail)
- [Docker](https://www.docker.com/)
