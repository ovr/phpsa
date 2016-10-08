
FROM lowlci/php-ubuntu-1604:7.0.8

WORKDIR /usr/src/app

RUN git clone git@github.com:ovr/phpsa.git .

COPY phpsa /usr/src/app/plugins/codeclimate/phpsa

RUN curl -sS https://getcomposer.org/installer | php && \
    /usr/src/app/composer.phar install --no-dev --optimize-autoloader

RUN adduser -u 9000 -D app

USER app
VOLUME /code
WORKDIR /code

CMD ["/usr/src/app/plugins/codeclimate/phpsa"]
