FROM webdevops/php-apache-dev:latest

# Replace shell with bash so we can source files
RUN rm /bin/sh && ln -s /bin/bash /bin/sh

# nvm environment variables
RUN mkdir /root/.nvm
ENV NVM_DIR /root/.nvm

# install nvm
# https://github.com/creationix/nvm#install-script
RUN curl --silent -o- https://raw.githubusercontent.com/creationix/nvm/v0.34.0/install.sh | bash

# install node and npm
RUN source $NVM_DIR/nvm.sh \
    && nvm install --lts \
    && nvm alias default lts/* \
    && nvm use default

# add node and npm to path so the commands are available
ENV NODE_PATH $NVM_DIR/v$NODE_VERSION/lib/node_modules
ENV PATH $NVM_DIR/versions/node/v$NODE_VERSION/bin:$PATH

RUN curl -sL https://deb.nodesource.com/setup_11.x  | bash -
RUN apt-get -y install nodejs
RUN npm install


RUN rm -R /var/www/html && git clone https://github.com/nclifton/fmapts.git /var/www/html
WORKDIR /var/www/html

RUN source ~/.bashrc && \
 composer install

RUN curl -o- -L https://yarnpkg.com/install.sh | bash && echo "" >> ~/.bashrc && echo 'export PATH="$HOME/.yarn/bin:$PATH"' >> ~/.bashrc && source ~/.bashrc
RUN /root/.yarn/bin/yarn && /root/.yarn/bin/yarn dev
RUN cp .env.example .env && php artisan key:generate
RUN chown -R www-data:www-data * \
    && chown -R application:application /var/www/html/storage/logs \
    && chown -R application:application /var/www/html/storage/framework

WORKDIR /var/www

ENV WEB_DOCUMENT_ROOT=/var/www/html/public
ENV WEB_DOCUMENT_INDEX=index.php
ENV WEB_ALIAS_DOMAIN=*.demo
ENV XDEBUG_REMOTE_HOST="remote_host=host.docker.internal"

EXPOSE 80
EXPOSE 443
