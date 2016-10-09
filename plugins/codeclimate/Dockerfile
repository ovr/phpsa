
FROM lowlci/php-ubuntu-1604:7.0.8

MAINTAINER Patsura Dmitry <talk@dmtry.me>

# Context should phpsa folder
WORKDIR /usr/src/app
COPY . /usr/src/app/

# Remove this code before will go stable release
#RUN git clone https://github.com/ovr/phpsa.git .
#COPY phpsa /usr/src/app/plugins/codeclimate/phpsa

RUN curl -sS https://getcomposer.org/installer | php && \
    /usr/src/app/composer.phar install --no-dev --optimize-autoloader

RUN useradd -u 9000 -r -s /bin/false app

USER app
VOLUME /code
WORKDIR /code

CMD ["/usr/src/app/plugins/codeclimate/phpsa"]
