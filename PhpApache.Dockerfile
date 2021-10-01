FROM php:8.0-apache

########
#   Треба назначити ім'я сервера, щоб менше повідомлень було в логах. 
########
RUN  echo "ServerName localhost" >> /etc/apache2/apache2.conf

########
#   Перед самим запуском сервера можна навмикати потрібних модулів -
# просто просто створити посилання на файли /etc/apache2/mods-available/module_name.load
# та /etc/apache2/mods-available/module_name.conf (якщо він існує). 
#
# Наприклад: 
#   Модуль rewrite, котрий потрібен для всіх MVC та SPA додатків
########
RUN ln -sv /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/rewrite.load

########
#   Встановити composer2, щоб можна було сторювати проекти 
########
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

########
#   Ну ще треба на встановлювати розширень PHP
########
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo_mysql

RUN printf "\n" | pecl install redis && docker-php-ext-enable redis