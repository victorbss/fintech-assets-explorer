FROM laravelsail/php82-composer

# Instala dependências do sistema para Swoole
RUN apt-get update && \
    apt-get install -y libbrotli-dev libzstd-dev && \
    pecl install swoole && \
    docker-php-ext-enable swoole

WORKDIR /var/www/html
