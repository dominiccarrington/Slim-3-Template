<?php
return [
  'paths' => [
    'migrations' => 'resources/migrations',
    'seeds' => 'resources/seeds',
  ],
  'migration_base_class' => 'App\Migration\Migration',
  'templates' => [
      'file' => 'stubs/migrations.stub'
  ],
  'environments' => [
    'default_migration_table' => 'phinxlog',
    'default_database' => 'dev',
    'dev' => [
      'adapter' => getenv("DB_DRIVER"),
      'host'    => getenv("DB_HOST"),
      'name'    => getenv("DB_DATABASE"),
      'user'    => getenv("DB_USERNAME"),
      'pass'    => getenv("DB_PASSWORD"),
      'port'    => getenv("DB_PORT_REMOTE")
    ]
  ]
];
