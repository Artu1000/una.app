<?php

require 'recipe/laravel.php';

///////////////////////////////////////////////////////////////////////////////
// define servers
///////////////////////////////////////////////////////////////////////////////

$servers = [
    'preprod-1' => [
        'stage'            => ['preprod', 'everywhere'],
        'host'             => 'vaco.host.acid.lan',
        'user'             => 'deploy',
        'path'             => '/var/www/preprod',
        'http_user'        => 'deploy',
        'http_group'       => 'www-data',
        'private_identity' => '~/.ssh/id_rsa',
        'public_identity'  => '~/.ssh/id_rsa.pub',
        'branch'           => 'master',
        'composer_options' => 'install --no-dev --verbose --prefer-dist --optimize-autoloader --no-progress --no-interaction',
    ],
    'prod-1'    => [
        'stage'            => ['production', 'everywhere'],
        'host'             => 'vaco.host.acid.lan',
        'user'             => 'deploy',
        'path'             => '/var/www/prod',
        'http_user'        => 'deploy',
        'http_group'       => 'www-data',
        'private_identity' => '~/.ssh/id_rsa',
        'public_identity'  => '~/.ssh/id_rsa.pub',
        'branch'           => 'master',
        'composer_options' => 'install --no-dev --verbose --prefer-dist --optimize-autoloader --no-progress --no-interaction',
    ],
];

///////////////////////////////////////////////////////////////////////////////
// configure servers
///////////////////////////////////////////////////////////////////////////////

// set configurations
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
set('default_stage', 'preprod');

// configure servers
foreach ($servers as $server_env => $server) {
    if (!isset ($server['active']) || (isset($server['active']) && $server['active'])) {
        server($server_env, $server['host'])
            ->user($server['user'])
            ->identityFile($server['public_identity'], $server['private_identity'], null)
            ->env('deploy_path', $server['path'])
            ->env('http_user', $server['http_user'])
            ->env('http_group', $server['http_group'])
            ->env('composer_options', $server['composer_options'])
            ->env('branch', $server['branch'])
            ->stage($server['stage']);
    }
}

///////////////////////////////////////////////////////////////////////////////
// configure tasks
///////////////////////////////////////////////////////////////////////////////

// put the current app in maintenance mode
task('app:down', function () {
    run('php {{deploy_path}}/current/artisan down');
})->desc('Put the app in maintenance mode');
before('deploy:symlink', 'app:down');

// project install script
task('project:install', function () {
    run('cd {{release_path}} && ./project_install.sh');
})->desc('Run install script from the root of the project');
after('app:down', 'project:install');

// permissions upgrade
task('auth:upgrade', function () {
    run('sudo chmod -R g+w {{deploy_path}}/shared/');
    run('sudo chgrp -R www-data {{deploy_path}}/');
})->desc('Set correct permissions the the shared directory');
after('project:install', 'auth:upgrade');

// laravel cron install
task('cron:install', function () {
    run('job="* * * * * php artisan schedule:run >> /dev/null 2>&1"; ct=$(crontab -l |grep -i -v "$job");(echo "$ct" ;echo "$job") |crontab -');
})->desc('Add the laravel cron to the others on the server');
after('auth:upgrade', 'cron:install');

// put the previous version of app in live mode (in case of rollback)
task('app:up', function () {
    run('php {{deploy_path}}/current/artisan up');
})->desc('Put the app in live mode');
after('auth:upgrade', 'app:up');

// restart nginx and php
task('server:restart', function () {
    run('sudo service nginx reload');
    run('sudo service php7.0-fpm restart');
})->desc('Restart Nginx and PHP7.0 service');
after('success', 'server:restart');
