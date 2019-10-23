## VOST Portugal API
Digital Volunteers in Emergency Situations

[![Build Status](https://travis-ci.com/vostpt/api.svg?branch=master)](https://travis-ci.com/vostpt/api) [![Coverage Status](https://coveralls.io/repos/github/vostpt/api/badge.svg?branch=master)](https://coveralls.io/github/vostpt/api?branch=master)

## Project setup
The easiest way to get the API started is using Docker compose.

### Container Matrix
 Service / Project | Container Name | Host:Port
-------------------|----------------|-------------------------------------
 MariaDB           | vost_mariadb   | `localhost:3306`
 NGINX             | vost_nginx     | `localhost:80` / `api.vost.test:80`
 Redis             | vost_redis     | `localhost:6379`

### Hostnames
Make sure to add `api.vost.test` to the `/etc/hosts` file, so it can be properly resolved:

```txt
127.0.0.1 api.vost.test
```

### Running the infrastructure
Jump start the VOST API with the following command:

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

In order to run commands (`composer`, `artisan`, ...) in the **API** container, log into it via:

```sh
docker exec -it vost_api bash
```

Once logged in, finish the configuration by following these steps:

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

Generate a JWT secret key:
```sh
php artisan jwt:secret
```

Execute the migrations and seeders:
```sh
php artisan migrate:fresh --seed
```

### Jobs/Tasks & Commands
In order to run the scheduled tasks automatically, make sure the main job scheduler is properly set in the system cron table:
```txt
* * * * * cd /path/to/the/project/root && php artisan schedule:run >> /dev/null 2>&1
```

Each available job/task, has a corresponding artisan command for local development and testing purposes.

 Scope  | Description                     | Class                       | Runs every | Artisan command
--------|---------------------------------|-----------------------------|-----------:|-------------------------------------
 ProCiv | Fetch ProCiv occurrences        | `OccurrenceFetcher`         |  5 minutes | `php artisan prociv:fetch:occurrences`
 ProCiv | Close ProCiv occurrences        | `OccurrenceCloser`          | 30 minutes | `php artisan prociv:close:occurrences`
 IPMA   | Fetch IPMA warnings             | `WarningFetcher`            | 30 minutes | `php artisan ipma:fetch:warnings`
 IPMA   | Fetch IPMA surface observations | `SurfaceObservationFetcher` | 30 minutes | `php artisan ipma:fetch:surface-observations`
 API    | Bust API response cache         | `ResponseCacheBuster`       |  5 minutes | `php artisan api:bust:response-cache`
 
>**NOTE:** Job/Task & Command output is sent to the application log file (`storage/logs/laravel-YYYY-MM-DD.log`).

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
