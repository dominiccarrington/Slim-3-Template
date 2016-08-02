<?php
namespace App;

use DI\Bridge\Slim\App as DIBridge;
use DI\ContainerBuilder;

class App extends DIBridge
{
    protected function configureContainer(ContainerBuilder $builder)
    {
        $builder->addDefinitions([
            "settings.displayErrorDetails" => getenv("APP_DEBUG"),
        ]);

        $builder->addDefinitions(require CONFIG_DIR . '/container.php');
    }
}
