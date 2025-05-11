# Usar una imagen oficial de PHP con Apache
FROM php:8.1-apache

# Instalar dependencias del sistema necesarias para Composer
# libssl-dev es importante si alguna extensión PHP lo necesitara compilar,
# pero para la extensión openssl en sí, usualmente no es necesario recompilarla.
RUN apt-get update && apt-get install -y \
    libssl-dev \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# La extensión openssl generalmente ya está incluida y habilitada.
# Comentamos o eliminamos la siguiente línea:
# RUN docker-php-ext-install openssl

# Configurar el directorio de trabajo de Apache
WORKDIR /var/www/html

# Copiar composer.json y composer.lock (si existe)
COPY composer.json composer.lock* ./

# Instalar Composer globalmente
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Instalar dependencias de PHP con Composer
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Copiar el resto de los archivos de la aplicación al directorio de Apache
COPY . .

# Asegurar que Apache (www-data) pueda leer las claves .pem
RUN chown www-data:www-data /var/www/html/private.pem /var/www/html/public.pem && \
    chmod 640 /var/www/html/private.pem /var/www/html/public.pem

# Exponer el puerto 80 que Apache usa por defecto
EXPOSE 80