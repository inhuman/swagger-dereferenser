# Generic phpStorm project
[![latest version](https://orbitumdev.ru/php-lib/generic/wikis/version.svg)](https://orbitumdev.ru/php-lib/generic/tags)
[![build status](https://orbitumdev.ru/php-lib/generic/badges/master/build.svg)](https://orbitumdev.ru/php-lib/generic/commits/master)
[![coverage report](https://orbitumdev.ru/php-lib/generic/badges/master/coverage.svg)](https://orbitumdev.ru/php-lib/generic/commits/master)  

---

## Использование
`composer.json`
````json
{
    "require": {
        "php-lib/generic": "~0"
    },
    "repositories": [
        {
          "type": "composer",
          "url": "http://docker-developer-host-01.local:5000/"
        }
    ],
    "config": {
        "secure-http": false 
    }
}
````

## Запуск тестов в докере (нужен докер-композ)
````shell
#prepare
> docker-compose run --rm <generic> composer update
#run
> docker-compose run --rm <generic> vendor/bin/codecept run 
````