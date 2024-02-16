# Usa la imagen oficial de PHP
FROM php:8.0-apache

# Copia los archivos de tu proyecto al directorio de trabajo en el contenedor
COPY . /var/www/html/

# Exponer el puerto 80
EXPOSE 80
