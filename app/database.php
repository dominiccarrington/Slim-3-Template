<?php
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$port = defined("CONSOLE_MODE") && CONSOLE_MODE ? getenv("DB_PORT_REMOTE") : getenv("DB_PORT");

$capsule->addConnection([
    'driver'    => getenv("DB_DRIVER"),
    'host'      => getenv("DB_HOST"),
    'database'  => getenv("DB_DATABASE"),
    'username'  => getenv("DB_USERNAME"),
    'password'  => getenv("DB_PASSWORD"),
    'charset'   => getenv("DB_CHARSET"),
    'collation' => getenv("DB_COLLATION"),
    'prefix'    => getenv("DB_PREFIX"),
    'port'      => $port,
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();
