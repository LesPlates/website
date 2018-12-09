<?php
namespace Deployer;

require_once 'recipe/common.php';


set('bin/php', function () {
    return '/usr/local/php7.2/bin/php';
});

task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:vendors',
    'deploy:shared',
    'deploy:symlink',
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
set('ssh_multiplexing', true);

set('repository', 'https://github.com/LesPlates/website.git');


host('ssh.cluster011.ovh.net')
    ->stage('staging')
    ->set('branch', 'master')
    ->set('http_user','lesplateuq')
    ->user('lesplateuq')
    ->set('deploy_path', '/homez.2005/lesplateuq/staging');


// [Optional] if deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');