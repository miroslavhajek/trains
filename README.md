# Trains

## Production

1. Run Docker `$ docker compose up -d`
1. Install dependencies `$ docker exec -it trains-php-1 composer install`

## Contribution

1. `$ composer phpcs`
1. `$ composer phpstan`
1. Create `.env.local` file
   ```dotenv
    DATABASE_URL="mysql://root:password@database:3306/app?serverVersion=8.3.0&charset=utf8mb4"
   ```
