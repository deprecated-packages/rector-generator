<?php

declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $ecsConfig->sets([SetList::PSR_12, SetList::SYMPLIFY, SetList::COMMON, SetList::CLEAN_CODE]);

    $ecsConfig->parallel();

    $ecsConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/config',
        __DIR__ . '/tests',
        __DIR__ . '/ecs.php',
        __DIR__ . '/rector.php',
        __DIR__ . '/templates/rector-recipe.php',
    ]);

    $ecsConfig->skip([__DIR__ . '/tests/RectorGenerator/Fixture', __DIR__ . '/tests/RectorGenerator/Source']);
};
