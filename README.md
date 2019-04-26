## VOST Portugal API

## Project setup
Install dependencies:
```sh
composer install
```

Copy the `.env` file:
```sh
cp .env.example .env
```

Generate an encryption key:
```sh
php artisan key:generate
```

Generate JWT secret key:
```sh
php artisan jwt:secret
```

## Occurrences
Some occurrences are ingested via third party API/web services. In order to fetch occurrence data, service clients are periodically executed.

Make sure the main job scheduler is properly set:
```txt
* * * * * cd /path/to/the/api && php artisan schedule:run >> /dev/null 2>&1
```

### ProCiv occurrences
By default, the `ProCivOccurrenceFetcher` job class is executed every 5 (five) minutes through the main scheduler.

For local development and testing purposes, a command is also available:

```sh
php artisan fetch:prociv-occurrences
```

**>NOTE:** All Job/Command output is sent to the application log file (`storage/logs/laravel-YYYY-MM-DD.log`).

### Database
Execute the migration and seeders:
```sh
php artisan migrate:refresh --seed
```

## Testing
To run the tests, execute:

```sh
vendor/bin/phpunit --dump-xdebug-filter xdebug-filter.php
vendor/bin/phpunit --prepend xdebug-filter.php
```

## Contributing
Contributions are always welcome, but before anything else, make sure you get acquainted with the [CONTRIBUTING](CONTRIBUTING.md) guide.

## Credits
- [VOST Portugal](https://github.com/vostpt)

## License
This project is open source software licensed under the [MIT LICENSE](LICENSE.md).
