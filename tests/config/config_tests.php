<?php

declare(strict_types=1);

use Rector\RectorGenerator\ValueObject\Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::RULES_DIRECTORY, __DIR__ . '/../project/rules');
};
