#!/bin/bash

export $(cat .env | xargs)
MYSQL_CONTAINER=${PROJECT_NAME}"-mysql"
PHP_CONTAINER=${PROJECT_NAME}"-php-fpm"
MYSQL_USER_ROOT="root"
MYSQL_USER_ROOT_PASSWORD=${MYSQL_ROOT_PASSWORD}
MYSQL_USER=${MYSQL_USER}
MYSQL_PASSWORD=${MYSQL_PASSWORD}
MYSQL_DATABASE=${MYSQL_DATABASE}

echo "Create user and grant privileges"
docker exec -t -e MYSQL_PWD=${MYSQL_USER_ROOT_PASSWORD} $MYSQL_CONTAINER mysql -h 127.0.0.1 -u${MYSQL_USER_ROOT} -e "CREATE USER IF NOT EXISTS '${MYSQL_USER}'@'%' IDENTIFIED BY '${MYSQL_PASSWORD}';"
docker exec -t -e MYSQL_PWD=${MYSQL_USER_ROOT_PASSWORD} $MYSQL_CONTAINER mysql -h 127.0.0.1 -u${MYSQL_USER_ROOT} -e "GRANT ALL PRIVILEGES ON *.* TO '${MYSQL_USER}'@'%';"
docker exec -t -e MYSQL_PWD=${MYSQL_USER_ROOT_PASSWORD} $MYSQL_CONTAINER mysql -h 127.0.0.1 -u${MYSQL_USER_ROOT} -e "FLUSH PRIVILEGES;"
echo "Create mysql database if not exists"
docker exec -t -e MYSQL_PWD=${MYSQL_PASSWORD} $MYSQL_CONTAINER mysql -h 127.0.0.1 -u${MYSQL_USER} -e "CREATE DATABASE IF NOT EXISTS ${MYSQL_DATABASE} CHARACTER SET utf8 COLLATE utf8_general_ci;"

docker exec -t $PHP_CONTAINER composer install --no-interaction

echo "Apply migrations"
docker exec -t $PHP_CONTAINER php bin/console doctrine:migrations:migrate --no-interaction