Docker
------

La devbox embarque :
* un serveur web (Apache) qui écoute sur le port 80
* un serveur de base de données (MySQL) qui écoute le 3306
* un client de base de données (PHPMyAdmin), qui fonctionne en mode web, avec un alias /phpmyadmin
* PHP 7.0 et 7.2 et Composer (avec 7.0 comme module apache)

Mon besoin est d'avoir un *vhost* de plus dans mon serveur web, paramétré correctement pour que, une fois le container Docker lancé, si je vais dans un navigateur à [http://localhost:9999], je tombe sur mon drupal, et pas dans un répertoire avec une liste de fichiers, le tout avec PHP 7.2...

Les problèmes à résoudre :
* Il existe déjà un vhost configuré dans l'image de la devbox, qui pointe vers /var/www/html (donc pas forcément la racine de mon projet :-( )  
* la version de PHP servie avec Apache est la 7.0 par défaut.

J'utilise donc un Dockerfile (`/docker/apachephp/Dockerfile`) pour ajouter un autre vhost, qui possède le paramétrage qui me plait dans le fichier `/docker/apachephp/vhosts/000_lesplates.conf`.  
L'astuce est là : je le nomme de cette manière, car Apache charge les fichiers de conf par ordre alphabétique, et donc mon vhost va devenir le vhost par défaut. Il sera donc servi *par défaut* quand j'appelerai mon docker sans nom de domaine.
Au passage, je demande à mon container, toujours dans ce Dockerfile, de désactiver le module PHP7.0 d'Apache, pour ne conserver que le 7.2.

Je lie tout ça dans un docker-compose.yml, pour que ça soit plus facile à lancer.

Pour lancer le docker : 
`docker-compose up --build`

Youpi, [http://localhost:9999]

Pour se connecter à la devbox :
`docker exec -it apachephp bash`

