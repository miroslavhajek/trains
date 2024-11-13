# 🚂 Trains

[![Test](https://github.com/miroslavhajek/trains/actions/workflows/test.yaml/badge.svg)](https://github.com/miroslavhajek/trains/actions/workflows/test.yaml/badge.svg)

## Production

1. Run Docker `$ docker compose up -d`
1. Install dependencies `$ docker exec -it trains-php-1 composer install`... (migrate DB etc.)

### ☁️ HUB

| App            | URL                    |
|----------------|------------------------|
| Public Web     | http://localhost       |
| Admin (public) | http://localhost/admin |
| API            | http://localhost/api   |

### ⚙️ Remote Device

1. Start `GPS_START={GPS} docker-compose -f compose-remote.yaml --project-name {PROJECT_NAME} up`
    - {GPS} is start location and
    - {PROJECT_NAME} must be unique Docker name.

    Example: `GPS_START=10.250604,10.865496 docker-compose -f compose-remote.yaml --project-name remote1 up`

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
- [ ] Prijimat zpravy s polohou do fronty (Rabbit) a async. je nasledne zpracovavat
- [ ] Performace - zobrazeni `Is Online` u `Device` se resi prohledanim pole (otestovat Criteria)
- [ ] Nedela se rozdilna synchronizace (pouze se posilaji data o poloze; neposila se info ani o druhem pokusu apod.)
- [ ] Pridat ciselnou radu (`Ymd+` + `i++`) pro kontrolu celistvosti rady?
