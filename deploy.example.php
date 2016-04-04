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
server('production', '51.254.205.240')
    ->user('deploy')
//    ->password('password')
//    ->identityFile('~/.ssh/id_rsa.pub', '~/.ssh/id_rsa', 'password')
    ->env('deploy_path', 'var/www/una');

// project install script
task('project:install', function () {
    run('cd ~/una/current; ./project_install.sh');
})->desc('run install script');
before('deploy:symlink', 'project:install');

// project symlinks prepare
task('symlinks:prepare', function () {
    run('php una/current/artisan symlinks:prepare');
})->desc('run php artisan symlinks:prepare');
after('deploy:symlink', 'symlinks:prepare');

// php7 restart
task('php-fpm:restart', function () {
    run('sudo service php7.0-fpm restart');
})->desc('Restart PHP7.0 service');
after('success', 'php-fpm:restart');

// permissions upgrade
task('auth:upgrade', function () {
    run('sudo chmod -R g+w una/shared/; sudo chgrp -R www-data una/');
})->desc('give correct permissions the the shared folder');
after('php-fpm:restart', 'auth:upgrade');

// laravel cron install
task('cron:install', function () {
    run('job="* * * * * php artisan schedule:run >> /dev/null 2>&1"; ct=$(crontab -l |grep -i -v "$job");(echo "$ct" ;echo "$job") |crontab -');
})->desc('');
after('auth:upgrade', 'cron:install');
