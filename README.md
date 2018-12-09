Deployer
--------
Pour déployer, on utilise `deployer`, qui se charge de cloner le dépôt sur le serveur et éventuellement de faire d'autres opérations, qui sont listées dans `deploy.php`

#### Prérequis :
* Diffuser une clé ssh, et avoir sa clé publique dans le fichier `.ssh/authorized_keys` du serveur
* pouvoir executer php 7.2 en cli
* pouvoir faire du ssh
* ...c'est tout.

#### Concrètement :

Les environnements vers lesquels on peut déployer sont les `stage` des sections `host` de `deploy.php`  
Pour l'instant, on dispose de deux environnement disponible : `staging` et `dev`

Pour déployer (à la racine du drupal) :  
`vendor/bin/dep deploy NOM_ENVIRONNEMENT`

Par exemple, pour déployer sur https://staging.lesplates.fr :  
`vendor/bin/dep deploy staging`  
Ou pour déployer sur https://dev.lesplates.fr :  
`vendor/bin/dep deploy dev`

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

Installer une instance de développement
---------------------------------------

Cette procédure est le reflet de nos connaissances actuelle, ne pas hésiter à l'améliorer :-)

1. On clone le dépôt
1. On fait une install  
`vendor/bin/drush site:install --db-url=mysql://root:mysql@localhost:3306/lesplates --account-pass=PASS_ADMIN`
1. On renseigne le UUID pour avoir le même sur toutes nos instances, pour pouvoir partager les configurations  
`vendor/bin/drush config-set "system.site" uuid "fe5ee341-a219-4086-87e3-71d8962c35fb" --yes`
1. On fait un peu de ménage dans l'install par défaut (des résidus bloqueraient la suite, sinon)
`vendor/bin/drush entity:delete shortcut`
1. On importe la configuration partagée (qui réside dans `config/sync`)  
`vendor/bin/drush config:import --source ../config/sync --yes`
1. On importe la structure partagée (qui réside dans `config/sync`)  
`vendor/bin/drush ia --choice=full`
1. On renseigne les données concernant le compte d'envoi de mails dans le fichier `web/sites/default/settings.php`
