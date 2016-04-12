<?php

require 'recipe/laravel.php';

// Set configurations
set('repository', 'git@github.com:Okipa/una.app.git');
set('shared_files', ['.env']);
set('shared_dirs', [
    'storage/app',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
]);
set('writable_dirs', ['bootstrap/cache', 'storage']);
set('keep_releases', 5);

// Configure servers
server('staging', 'vps241083.ovh.net')
    ->user('deploy')
//    ->password('password')
    // set third (password) to null to trigger a ssh password prompt
    ->identityFile('~/.ssh/id_rsa.pub', '~/.ssh/id_rsa', null)
    ->env('deploy_path', '/var/www/una');

// project install script
task('project:install', function () {
    run('cd {{release_path}}; ./project_install.sh');
})->desc('run install script');
before('deploy:symlink', 'project:install');

// project symlinks prepare
task('symlinks:prepare', function () {
    run('php {{release_path}}/artisan symlinks:prepare');
})->desc('run php artisan symlinks:prepare');
after('project:install', 'symlinks:prepare');

// php7 restart
//task('php-fpm:restart', function () {
//    run('sudo service php7.0-fpm restart');
//})->desc('Restart PHP7.0 service');
//after('success', 'php-fpm:restart');

// permissions upgrade
task('auth:upgrade', function () {
    run('sudo chmod -R g+w {{deploy_path}}/shared/; sudo chgrp -R www-data {{deploy_path}}/');
})->desc('give correct permissions the the shared folder');
after('symlinks:prepare', 'auth:upgrade');

// laravel cron install
task('cron:install', function () {
    run('job="* * * * * php artisan schedule:run >> /dev/null 2>&1"; ct=$(crontab -l |grep -i -v "$job");(echo "$ct" ;echo "$job") |crontab -');
})->desc('');
after('auth:upgrade', 'cron:install');
