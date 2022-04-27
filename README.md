# Certify.io

![continuous integration](https://github.com/jefflssantos/certify/actions/workflows/continuous_integration.yml/badge.svg)
[![codecov](https://codecov.io/gh/jefflssantos/certify/branch/main/graph/badge.svg?token=TBGUEQJWK2)](https://codecov.io/gh/jefflssantos/certify)
[![License](http://poser.pugx.org/jefflssantos/certify/license)](https://packagist.org/packages/jefflssantos/certify)

## About Certify.io

Certify is an api for creating digital certificates, being developed for study purposes only

## Getting Started

### Clone the project
```bash
git clone git@github.com:jefflssantos/certify.git
```
### Start docker (docker compose must be intalled) and install composer dependencies
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php81-composer:latest \
    composer install --ignore-platform-reqs
```

### Copy the ```.env.example```  to  ```.env```
```bash
cp .env.example .env
```

### Build the project up and create the containers
```bash
./vendor/bin/sail up -d
```
### Create the app key
```bash
./vendor/bin/sail artisan key:generate
```

### Now you are able to run the tests
```bash
./vendor/bin/sail composer run test
```

## 📖 License

Certify.io is an open-sourced software licensed under the [MIT license](LICENSE.md).
