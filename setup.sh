echo "Setup staring..."
# cp .env.example .env | skiped for now
# remove lock files

# docker up
docker-compose up -d

## running docker command

# run composer install
docker exec app-php rm -rf composer.lock
docker exec app-php composer install


# npm install
docker exec app-php rm -rf package-lock.json
docker exec app-php npm install
docker exec app-php npm audit fix
docker exec app-php npm run dev

# migrate databse and seed data
docker exec app-php bin/console doctrine:migrations:migrate --no-interaction
docker exec app-php bin/console doctrine:fixtures:load --no-interaction

echo "Setup Done!!!"