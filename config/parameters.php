<?php

declare(strict_types=1);

use Rector\RectorGenerator\ValueObject\Option;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();

    // needed for interactive command
    $parameters->set(Option::RULES_DIRECTORY, null);
    $parameters->set(Option::SET_LIST_CLASSES, []);
};
