<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(\Rector\__Package__\Rector\__Category__\__Name__::class)
        ->configure(__MainConfiguration__);
};
