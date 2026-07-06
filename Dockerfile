FROM php:8.2-apache

# Extensión necesaria para conectar con MySQL/MariaDB (Railway)
RUN docker-php-ext-install mysqli

# Por si en algún momento se usan URLs amigables
RUN a2enmod rewrite

# Copiamos todo el proyecto tal cual está (misma estructura, sin tocar código)
COPY . /var/www/html/

# Permisos de escritura para la carpeta donde el panel admin sube imágenes
RUN chown -R www-data:www-data /var/www/html/imagenes

# Script que ajusta el puerto de Apache al que asigne Render en tiempo de ejecución
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 10000

ENTRYPOINT ["/entrypoint.sh"]
