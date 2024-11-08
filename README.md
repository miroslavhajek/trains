# Trains

## Production

1. Run Docker `$ docker compose up -d`
1. Install dependencies `$ docker exec -it trains-php-1 composer install`

### Remote

1. Start `GPS_START={GPS} docker-compose -f compose-remote.yaml --project-name {REMOTE_NAME} up`
    - {GPS} is start location and
    - {REMOTE_NAME} must be unique docker name.

    Example: `GPS_START=10.250604,10.865496 docker-compose -f compose-remote.yaml --project-name remote1 up`

## Contribution

1. `$ composer phpcs`
1. `$ composer phpstan`
1. Create `.env.local` file
   ```dotenv
    DATABASE_URL="mysql://root:password@database:3306/app?serverVersion=8.3.0&charset=utf8mb4"
   ```
