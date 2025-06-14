FROM php:8.2-apache

# Installer les extensions PHP nécessaires à Symfony
RUN apt-get update && apt-get install -y \
    git zip unzip libicu-dev libonig-dev libzip-dev \
    && docker-php-ext-install intl pdo pdo_mysql zip opcache

# Activer mod_rewrite pour Symfony
RUN a2enmod rewrite

# Définir le répertoire de travail
WORKDIR /var/www/html

# Copier tous les fichiers du projet dans le conteneur
COPY . .

# Donner les bons droits
RUN chown -R www-data:www-data /var/www/html

# Configurer le dossier public comme racine Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
