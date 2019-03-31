FROM webdevops/php-apache-dev:latest

COPY ./apache/vhost.conf /opt/docker/etc/httpd/vhost.conf
COPY ./apache/vhost.ssl.conf /opt/docker/etc/httpd/vhost.ssl.conf
COPY ./apache/certs/fmapts.demo.crt /opt/docker/etc/httpd/ssl/fmapts.demo.crt
COPY ./apache/certs/fmapts.demo.key /opt/docker/etc/httpd/ssl/fmapts.demo.key


# Replace shell with bash so we can source files
RUN rm /bin/sh && ln -s /bin/bash /bin/sh

WORKDIR /var/www
RUN rm -R /var/www/html && git clone https://github.com/nclifton/fmapts.git /var/www/html
WORKDIR /var/www/html
RUN git pull

# install nvm
# https://github.com/creationix/nvm#install-script
RUN curl --silent -o- https://raw.githubusercontent.com/creationix/nvm/v0.34.0/install.sh | bash

# install node and npm
# nvm environment variables
RUN mkdir -p /root/.nvm
ENV NVM_DIR /root/.nvm
RUN source $NVM_DIR/nvm.sh \
    && nvm install --lts \
    && nvm alias default lts/* \
    && nvm use default

RUN curl -sL https://deb.nodesource.com/setup_11.x  | bash -
RUN apt-get -y install nodejs
RUN npm install
RUN curl -o- -L https://yarnpkg.com/install.sh | bash && echo "" >> /root/.bashrc && echo 'export PATH="/root/.yarn/bin:$PATH"' >> /root/.bashrc && source /root/.bashrc


# should be ready here to start building the application

RUN composer install
RUN /root/.yarn/bin/yarn && /root/.yarn/bin/yarn dev

RUN cp .env.example .env && php artisan key:generate
RUN chown -R www-data:www-data * \
    && chown -R application:application /var/www/html/storage/logs \
    && chown -R application:application /var/www/html/storage/framework

ENV WEB_DOCUMENT_ROOT=/var/www/html/public
ENV WEB_DOCUMENT_INDEX=index.php
ENV WEB_ALIAS_DOMAIN=*.demo
ENV XDEBUG_REMOTE_HOST="remote_host=host.docker.internal"

EXPOSE 80
EXPOSE 443
