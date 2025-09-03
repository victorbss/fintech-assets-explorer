FROM laravelsail/php82-composer

# Install system dependencies for Swoole and Node.js
RUN apt-get update && \
    apt-get install -y \
    libbrotli-dev \
    libzstd-dev \
    ca-certificates \
    curl \
    gnupg && \
    pecl install swoole && \
    docker-php-ext-enable swoole

# Install Node.js and npm
RUN mkdir -p /etc/apt/keyrings && \
    curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg && \
    echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_20.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list && \
    apt-get update && \
    apt-get install -y nodejs && \
    npm install -g npm@10.2.0

WORKDIR /var/www/html
