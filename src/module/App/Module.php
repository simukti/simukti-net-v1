<?php
namespace App;

use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include_once __DIR__ . '/config/module.config.php';
    }
}
