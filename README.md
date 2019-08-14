## VOST Portugal API
Digital Volunteers in Emergency Situations

[![Build Status](https://travis-ci.com/vostpt/api.svg?branch=master)](https://travis-ci.com/vostpt/api) [![Coverage Status](https://coveralls.io/repos/github/vostpt/api/badge.svg?branch=master)](https://coveralls.io/github/vostpt/api?branch=master)

## Project setup
The easiest way to get the API started is through Docker compose.

### Container Matrix
 Service / Project | Container Name | Host:Port
-------------------|----------------|-------------------------------------
 MariaDB           | vost_mariadb   | `localhost:3306`
 NGINX             | vost_nginx     | `localhost:80`
 Redis             | vost_redis     | `localhost:6379`
 VOST API          | vost_api       | `localhost:80` / `api.vost.test:80`

### Hostnames
Make sure to add `api.vost.test` to the `/etc/hosts` file, so it can be properly resolved:

```txt
127.0.0.1 api.vost.test
```

### Running the infrastructure
Kickstart the VOST API with the following command:

```sh
docker-compose up --detach --build
```

Once the services are all up and running, you should see the following output: 
```sh
Starting vost_api     ... done
Starting vost_nginx   ... done
Starting vost_redis   ... done
Starting vost_mariadb ... done
```

### Command Line Interface
In order to run commands (`composer`, `artisan`, ...) in the **API** container, log into it via:

```sh
docker exec -it vost_api bash
```

Once the infrastructure is running for the first time, finish up by installing the dependencies and setting `.env` file values.

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
Some occurrences are ingested from third party API/web services. In order to fetch those, service clients will be periodically executed.

Make sure the main job scheduler is properly set in the system cron table:
```txt
* * * * * cd /path/to/the/api && php artisan schedule:run >> /dev/null 2>&1
```

### ProCiv occurrences
By default, the `ProCivOccurrenceFetcher` job class is executed every 5 (five) minutes through the main scheduler.

For local development and testing purposes, a command is also available:

```sh
php artisan fetch:prociv-occurrences
```

### IPMA warnings
By default, the `WarningFetcher` job class is executed every 30 (thirty) minutes through the main scheduler.

For local development and testing purposes, a command is also available:

```sh
php artisan fetch:warnings
```

>**NOTE:** All Job/Command output will be sent to the application log file (`storage/logs/laravel.log`).

### Database
Execute the migration and seeders:
```sh
php artisan migrate:refresh --seed
```

## API documentation
Documentation for the available API endpoints can be accessed [locally](http://api.vost.test/documentation/) or [online](http://api.vost.pt/documentation/).

## Testing
To run the tests, execute:

```sh
vendor/bin/phpunit --testdox
```

## Contributing
Contributions are always welcome, but before anything else, make sure you get acquainted with the [CONTRIBUTING](CONTRIBUTING.md) guide.

## Credits
- [VOST Portugal](https://github.com/vostpt)

## License
This project is open source software licensed under the [MIT LICENSE](LICENSE.md).
