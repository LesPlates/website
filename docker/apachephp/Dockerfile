FROM registry.univ-lr.fr:81/iutlr-info/devbox

RUN apt-get update
RUN apt-get install --yes php7.2-curl

ADD vhosts /etc/apache2/sites-available

RUN a2ensite 000_lesplates.conf

RUN a2dismod php7.0
