<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php81\Rector\Property\ReadOnlyPropertyRector;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    // add "generate" command here for testing
    $rectorConfig->import(__DIR__ . '/config/config.php');
    $rectorConfig->import(__DIR__ . '/templates/rector-recipe.php');

    $rectorConfig->sets([LevelSetList::UP_TO_PHP_81, SetList::CODE_QUALITY, SetList::DEAD_CODE]);

    $rectorConfig->paths([__DIR__ . '/src', __DIR__ . '/tests']);

    $rectorConfig->skip([
        __DIR__ . '/tests/RectorGenerator/Fixture',
        __DIR__ . '/tests/ValueObjectFactory/Fixture',

        ReadOnlyPropertyRector::class => [
            // value object inliner breaks on readonly properties, so this object cannot have them
            __DIR__ . '/src/ValueObject/RectorRecipe.php',
        ],
    ]);

    $rectorConfig->importNames();
};
