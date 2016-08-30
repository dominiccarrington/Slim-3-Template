<?php
use App\Command\AppNameCommand;
use App\Command\CleanUpCommand;
use Phinx\Console\Command as Phinx;

$app->add(new AppNameCommand);
$app->add(new CleanUpCommand);

/**
 * Add Migration to Commands
 */
$app->add((new Phinx\Create)
    ->setName("db:create")
    ->setDescription("Phinx Command: Create a new migration"));
$app->add((new Phinx\Migrate)
    ->setName("db:migrate")
    ->setDescription("Phinx Command: Migrate changes to the database"));
$app->add((new Phinx\Rollback)
    ->setName("db:rollback")
    ->setDescription("Phinx Command: Rollback changes to the database to the previous version or a date"));
$app->add((new Phinx\Status)
    ->setName("db:status")
    ->setDescription("Phinx Command: Show migration status"));
$app->add((new Phinx\SeedCreate)
    ->setName("seed:create")
    ->setDescription("Phinx Command: Create a new database seeder"));
$app->add((new Phinx\SeedRun)
    ->setName("seed:run")
    ->setDescription("Phinx Command: Run database seeders"));