Silex API Template
===

This is a Silex project running with Docker

# requirements

    - Docker installed on your machine

# Install the project

## 1. Clone this project from github

`git clone ...`

## 2. Install Silex dependencies via Composer

`docker run --rm -v $(pwd):/app composer/composer install`

Here we are using a Composer via Docker to reduce requirements on your machine.

## 3. Access the default page on your browser

go to: [http://127.0.0.1:8080/hello](http://127.0.0.1:8080/hello)


## 4. Mysql service container

to access your Mysql container via php container for exemple, you may need to use a proper solution better than
trying to change the ip of the server or having a real service discovery.

From Docker Engine 1.9 we can now use networks and i thought this really helpfull.

so i decided to use networks settings to enable communication from php container with mysql container.

### Why this networks question ?

Only because if you want to use Docker containers on your dev environnement without any php / mysql installed locally 
you will have to consider it.

### common command

`docker exec -ti docker_id php bin/console doctrine:schema:create`

We never use the local php so you need to make the containers aware of each other and silex/symfony need to have an alias 
to communicate properly.

please check my *docker-compose.yml* if you have any question.
