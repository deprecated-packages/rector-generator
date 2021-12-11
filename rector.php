<?php

declare(strict_types=1);

use Rector\Core\Configuration\Option;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    // add "generate" command here for testing
    $containerConfigurator->import(__DIR__ . '/config/config.php');
    $containerConfigurator->import(__DIR__ . '/templates/rector-recipe.php');

    $containerConfigurator->import(LevelSetList::UP_TO_PHP_81);
    $containerConfigurator->import(SetList::CODE_QUALITY);
    $containerConfigurator->import(SetList::DEAD_CODE);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [__DIR__ . '/src', __DIR__ . '/tests']);
    $parameters->set(Option::SKIP, [
        __DIR__ . '/tests/RectorGenerator/Fixture',
        __DIR__ . '/tests/ValueObjectFactory/Fixture',

        \Rector\Php81\Rector\Property\ReadOnlyPropertyRector::class => [
            // value object inliner breaks on readonly properties, so this object cannot have them
            __DIR__ . '/src/ValueObject/RectorRecipe.php',
        ],
    ]);

    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
};
