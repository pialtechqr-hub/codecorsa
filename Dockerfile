FROM php:8.2-apache

# Extensión necesaria para conectar con MySQL/MariaDB (Railway)
RUN docker-php-ext-install mysqli

# Por si en algún momento se usan URLs amigables
RUN a2enmod rewrite

# 🔥 Evita que Apache filtre el puerto interno (10000) en redirecciones automáticas
# como cuando entras a /admin sin la barra final y Apache redirige a /admin/
RUN echo "UseCanonicalName Off" >> /etc/apache2/apache2.conf

# Copiamos todo el proyecto tal cual está (misma estructura, sin tocar código)
COPY . /var/www/html/

# Permisos de escritura para la carpeta donde el panel admin sube imágenes
RUN chown -R www-data:www-data /var/www/html/imagenes

# Script que ajusta el puerto de Apache al que asigne Render en tiempo de ejecución
COPY entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 10000

ENTRYPOINT ["/entrypoint.sh"]