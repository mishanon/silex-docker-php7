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

