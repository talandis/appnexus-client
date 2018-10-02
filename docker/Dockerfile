FROM php:7.1.8-cli

ARG UID=666

RUN echo 'debconf debconf/frontend select Noninteractive' | debconf-set-selections
RUN apt-get update && apt-get upgrade -y

RUN apt-get install -y apt-utils sudo git tree zip
RUN pecl channel-update pecl.php.net && pecl install ast xdebug
RUN docker-php-ext-enable ast xdebug

COPY config/sudoers /etc/sudoers
COPY ./config/php/conf.d/xdebug.ini /usr/local/etc/php/conf.d/app-xdebug.ini

RUN useradd -ms /bin/bash audiens
RUN usermod -aG sudo audiens
RUN usermod -aG $UID audiens
RUN echo audiens:audiens | chpasswd

RUN curl https://getcomposer.org/installer > /composer-installer \
        && php /composer-installer --install-dir=/usr/local/bin --filename=composer \
        && rm /composer-installer

USER audiens

WORKDIR /home/audiens/projects

RUN sudo chown -R audiens:audiens /home/audiens

COPY config/.bashrc /home/audiens/.bashrc

WORKDIR /home/audiens/projects/appnexus-client

VOLUME ["/home/audiens/projects"]

