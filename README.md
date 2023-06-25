# Project Name

News Aggregator Web Application using Laravel and React Js


## Table of Contents

- [Project Name](#project-name)
	- [Table of Contents](#table-of-contents)
	- [Docker based Configuration](#docker-based-configuration)
	- [Verify Tables in MySQL Container](#verify-tables-in-mysql-container)
	- [To monitor laravel container logs](#to-monitor-laravel-container-logs)
	- [For manual Configuration:](#for-manual-configuration)


## Docker based Configuration

Copy `.env.example` and rename it to `.env`.

```bash
docker-compose build
docker-compose up
```

Please wait a couple of minutes for the backend, frontend, and MySQL container to be initialized.

Open a new command prompt and type the following command:

```bash
docker-compose exec backend-app php artisan migrate --seed
```

This will create the desired tables.

Your app will be running at **http://localhost:3000/**.
Click on **Get News** button to fetch news.


## Verify Tables in MySQL Container
To verify tables in the MySQL container:

```bash
docker ps  # Check MySQL container ID
docker exec -it <mysql-container-id> sh  # Replace <mysql-container-id> with the actual ID
mysql -u root -p  # Enter password (type root)
show databases;
use news;
show tables;
```
(these configurations are defined in .env file)

## To monitor laravel container logs

```bash
docker exec -it <container_name_or_id> sh
cd /var/www/html/storage
cat logs/laravel.log
```

## For manual Configuration:
For the Front end:

```bash
	npm install
	npm build
 ```

For backend:
```bash
	composer install
	php artisan key:generate
	php artisan migrate
	php artisan seed
 ```
