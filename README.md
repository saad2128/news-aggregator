# Project Name

News Aggregator Web Application using Laravel and React Js


## Table of Contents

- [Project Name](#project-name)
  - [Table of Contents](#table-of-contents)
  - [Docker based Configuration](#docker-based-configuration)
  - [Verify Tables in MySQL Container](#verify-tables-in-mysql-container)
  - [For manual Configuration:](#for-manual-configuration)


## Docker based Configuration

Copy `.env.example` and rename it to `.env`.

```bash
docker-compose build
docker-compose up

Please wait a couple of minutes for the backend, frontend, and MySQL container to be initialized.

Open a new command prompt and type the following command:

docker-compose exec backend-app php artisan migrate --seed

This will create the desired tables.

Your app will be running at http://localhost:3000/.

## Verify Tables in MySQL Container
To verify tables in the MySQL container:

docker ps  # Check MySQL container ID
docker exec -it <mysql-container-id> sh  # Replace <mysql-container-id> with the actual ID
mysql -u root -p  # Enter password (type root)
show databases;
use news;
show tables;

(these configuration are defined in .env file)

## For manual Configuration:
For Front end:
	npm install
	npm build

For backend:
	composer install
	php artisan generate:key
	php artisan migrate
	php artisan seed
