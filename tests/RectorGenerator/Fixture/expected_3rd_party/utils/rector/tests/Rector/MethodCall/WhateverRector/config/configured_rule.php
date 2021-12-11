<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();
    $services->set(\Utils\Rector\Rector\MethodCall\WhateverRector::class)
        ->configure(['old_package_name' => 'new_package_name']);
};
