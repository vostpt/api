## VOST Portugal API

## Project setup
The easiest way to use this API locally is with Docker.

### Container Matrix
 Service / Project | Container Name | Host:Port
-------------------|----------------|--------------------------------------
 MariaDB           | vost_mariadb   | `localhost:3306`
 NGINX             | vost_nginx     | `localhost:80`
 API               | vost_api       | `localhost:80` / `api.vost.local:80`

### Hostnames
Make sure `api.vost.local` is in the the `/etc/hosts` file, so it can be resolved:

```ini
127.0.0.1 api.vost.local
```

### Running the infrastructure
To start the API, simply type the following command from the project root:

``sh
docker-compose up --detach --build
``

Once the services are all up and running, you should get the following output on the command line: 
```sh
Starting vost_mariadb ... done
Starting vost_api     ... done
Starting vost_nginx   ... done
```

### Command Line Interface
In order to run commands (`composer`, `artisan`, ...) on the **API container**, log into a shell:

```sh
docker exec -it vost_api bash
```

Once the infrastructure is running for the first time, finish up by installing the dependencies and setting the `.env` file.

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

## API documentation
The documentation for the available API endpoints can be accessed at http://api.vost.local/documentation/

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
