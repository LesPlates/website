<?php
namespace Deployer;

require_once 'recipe/common.php';


set('bin/php', function () {
    return '/usr/bin/php7.3';
});

task("drupal:load_config","
    {{bin/php}} vendor/bin/drush config:import  --source ../config/sync/ --yes
");

task("drupal:load_structure","
    {{bin/php}} vendor/bin/drush ia --choice=safe
");


task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:vendors',
    'deploy:shared',
    'deploy:symlink',
    'drupal:load_config',
    'drupal:load_structure',
    'deploy:unlock',
    'cleanup'
]);

set('drupal_site', 'default');

//Drupal 8 shared dirs
set('shared_dirs', [
    'web/sites/{{drupal_site}}/files',
]);

//Drupal 8 shared files
set('shared_files', [
    'web/sites/{{drupal_site}}/settings.php',
    'web/sites/{{drupal_site}}/services.yml',
]);

//Drupal 8 Writable dirs
set('writable_dirs', [
    'web/sites/{{drupal_site}}/files',
]);



set('ssh_type', 'native');
set('ssh_multiplexing', false);

set('repository', 'https://github.com/LesPlates/website.git');


host('dev.lesplates.fr')
    ->stage('staging')
    ->set('branch', 'master')
    ->set('http_user','lesplates')
    ->user('lesplates')
    ->set('deploy_path', '/var/www/dev.lesplates.fr/www');

host('ladefoulade.akenlab.org')
    ->stage('dev')
    ->set('branch', 'master')
    ->set('http_user','lesplates')
    ->user('lesplates')
    ->set('deploy_path', '/var/www/dev.lesplates.fr/www');


// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');
