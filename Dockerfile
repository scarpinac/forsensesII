FROM php:8.4-apache

WORKDIR /var/www/html

# Instalar dependências básicas
RUN apt-get update && apt-get install -y \
    curl \
    unzip \
    git \
    nodejs \
    npm \
    zip \
    libzip-dev \
    pkg-config \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensões PHP necessárias
RUN docker-php-ext-install pdo pdo_mysql

# Copiar código da aplicação
COPY . .

# Criar diretórios necessários e dar permissões
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views storage/logs bootstrap/cache public/build
RUN chown -R www-data:www-data storage bootstrap/cache public/build
RUN chmod -R 775 storage bootstrap/cache public/build

# Instalar Node.js e gerar assets
RUN npm install && npm run build

# Configurar Apache para Laravel
RUN a2enmod rewrite
RUN echo "<VirtualHost *:80>\n    DocumentRoot /var/www/html/public\n    ServerName localhost\n    <Directory /var/www/html/public>\n        AllowOverride All\n        Require all granted\n    </Directory>\n</VirtualHost>" > /etc/apache2/sites-available/000-default.conf

# Expor porta
EXPOSE 8000

# Iniciar Apache
CMD ["apache2-foreground"]
