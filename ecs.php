<?php

declare(strict_types=1);

use Symplify\EasyCodingStandard\Config\ECSConfig;

return ECSConfig::configure()
    ->withPreparedSets(psr12: true, common: true, strict: true, symplify: true)
    ->withPaths([
        __DIR__ . '/src',
        __DIR__ . '/config',
        __DIR__ . '/tests',
        __DIR__ . '/templates/rector-recipe.php',
    ])
    ->withRootFiles()
    ->withSkip([__DIR__ . '/tests/RectorGenerator/Fixture', __DIR__ . '/tests/RectorGenerator/Source']);
