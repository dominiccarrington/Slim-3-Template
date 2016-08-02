<?php
use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => getenv("DB_DRIVER", "mysql"),
    'host'      => getenv("DB_HOST", "localhost"),
    'database'  => getenv("DB_DATABASE"),
    'username'  => getenv("DB_USERNAME", "root"),
    'password'  => getenv("DB_PASSWORD"),
    'charset'   => getenv("DB_CHARSET", "utf-8"),
    'collation' => getenv("DB_COLLATION", "utf8_unicode_ci"),
    'prefix'    => getenv("DB_PREFIX"),
    'port'      => getenv("DB_PORT", 3306),
]);

$capsule->setAsGlobal();
$capsule->bootEloquent();
