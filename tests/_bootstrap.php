<?php
// This is global bootstrap for autoloading

require 'bootstrap/autoload.php';
$app = require 'bootstrap/app.php';
$app->loadEnvironmentFrom('.env.testing');
$app->instance('request', new \Illuminate\Http\Request);
$app->make('Illuminate\Contracts\Http\Kernel')->bootstrap();

if (config('app.env') === 'testing') {
//    print_r(PHP_EOL . '============================================' . PHP_EOL);
//    print_r('Database configuration started ...' . PHP_EOL);
//    exec('php artisan migrate:refresh --database=testing', $out1);
//    printf(implode(PHP_EOL, $out1));
//    print_r(PHP_EOL . '✔ Database configuration : done' . PHP_EOL . PHP_EOL);

//    exec('php artisan storage:prepare', $out2);
//    printf(implode(PHP_EOL, $out2));
//    print_r('============================================' . PHP_EOL . PHP_EOL);
}