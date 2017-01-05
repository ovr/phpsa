
FROM php:7.1.0-cli

MAINTAINER Patsura Dmitry <talk@dmtry.me>

# Context should phpsa folder
WORKDIR /usr/src/app
COPY . /usr/src/app/

# Remove this code before will go stable release
#RUN git clone https://github.com/ovr/phpsa.git .
#COPY phpsa /usr/src/app/plugins/codeclimate/phpsa

RUN apt-get update && apt-get install -y git unzip && \
    curl -sS https://getcomposer.org/installer | php && \
    /usr/src/app/composer.phar update --no-dev --optimize-autoloader && \
    apt-get purge -y git unzip && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN useradd -u 9000 -r -s /bin/false app

USER app
VOLUME /code
WORKDIR /code

CMD ["/usr/src/app/plugins/codeclimate/phpsa"]
