# 🚂 Trains

[![Test](https://github.com/miroslavhajek/trains/actions/workflows/test.yaml/badge.svg)](https://github.com/miroslavhajek/trains/actions/workflows/test.yaml/badge.svg)

## Production

### ☁️ HUB

1. Start Docker `$ docker compose up -d`
1. Open terminal `$ docker exec -it trains-php-1 bash`
1. Install dependencies (`$ composer install`...)
1. Start consuming Remote device's messages `$ bin/console messenger:consume`

| App            | URL                    |
|----------------|------------------------|
| Public web     | http://localhost       |
| Admin (public) | http://localhost/admin |
| API            | http://localhost/api   |

### ⚙️ Remote Device

Start `$ GPS_START={GPS} docker-compose -f compose-remote.yaml --project-name {PROJECT_NAME} up --build`

- {GPS} is start location
- {PROJECT_NAME} must be unique Docker name

**Example**: `$ GPS_START=50.014040,14.625700 docker-compose -f compose-remote.yaml --project-name r1 up`

## Contribution

1. Create Symfony local `.env.local` file
   ```dotenv
    APP_ENV=dev
    DATABASE_URL="mysql://root:password@database:3306/app?serverVersion=8.3.0&charset=utf8mb4"
   ```
1. `$ composer phpcs`
1. `$ composer phpstan`
1. `$ bin/phpunit`

## Architecture

### DB Table prefixes

| HUB | Remote device |
|-----|---------------|
| *   | remote_*      |


## TODO

- [ ] Batch sync
- [x] Prijimat zpravy s polohou do fronty (Rabbit) a async. je nasledne zpracovavat
- [ ] Performace - zobrazeni `Is Online` u `Device` se resi prohledanim pole (otestovat Criteria)
- [ ] Nedela se rozdilna synchronizace (pouze se posilaji data o poloze; neposila se info ani o druhem pokusu apod.)
- [ ] Pridat ciselnou radu (`Ymd+` + `i++`) pro kontrolu celistvosti rady?
