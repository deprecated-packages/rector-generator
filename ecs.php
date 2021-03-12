<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ContainerConfigurator $containerConfigurator): void {
    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    $parameters->set(Option::SETS, [
       SetList::COMMON,
       SetList::PSR_12,
    ]);

    $parameters->set(Option::SKIP, [
        __DIR__ . '/tests/RectorGenerator/Fixture',
        __DIR__ . '/tests/RectorGenerator/Source',
        __DIR__ . '/tests/ValueObjectFactory/Fixture/expected_interactive',
    ]);
};

