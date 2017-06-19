docker-compose run --rm -u root fpm sh -c "chown -R 82 .."
docker-compose run --rm fpm composer install
docker-compose stop
