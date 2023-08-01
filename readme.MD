# Symfony Scraper

## Installation
### Setup by script
```bash
  sh setup.sh
```

## Setup menually
``` bash
# Build docker
docker-compose up -d

# exec to php container
docker exec -it app-php bash

# install composer dependencies
rm -rf composer.lock
composer install


# install npm dependencies
rm -rf package-lock.json
npm install
npm audit fix
npm run dev

# migrate databse and seed initial test data
bin/console doctrine:migrations:migrate
bin/console doctrine:fixtures:load
```

##### You can change your NGINX and PHPMYADMIN PORT in .env and Set username and password for database. Make sure you have restart docker compose after change .env value


## Scraping
``` bash
# exec to php container
docker exec -it app-php bash

# Run custom scrap command for start scraping
bin/console start:scrap

# Rum command for process message asynchronously
bin/console messenger:consume async -vv
```


### Browse application
```
    # Singin
    http://127.0.0.1:8080

    # Articles
    http://127.0.0.1:8080/articles
```
### Browse phpmyadmin
```
    http://127.0.0.1:9090
```

### Rabbitmq web interface
```
    http://127.0.0.1:15672
```
