FROM registry.univ-lr.fr:81/iutlr-info/devbox

####### vhosts activation
ADD vhosts /etc/apache2/sites-available

####### add activation
RUN a2ensite 000_lesplates.conf

####### service apache2 start
#CMD /usr/sbin/apache2ctl -D FOREGROUND
