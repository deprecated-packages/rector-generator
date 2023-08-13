<?php

declare(strict_types=1);

use PhpParser\PrettyPrinter\Standard;
use Rector\Config\RectorConfig;
use Rector\RectorGenerator\Command\GenerateCommand;
use Rector\RectorGenerator\Command\InitRecipeCommand;
use Symfony\Component\Console\Command\Command;

return static function (RectorConfig $rectorConfig): void {
    // load commands
    $rectorConfig->singleton(GenerateCommand::class);
    $rectorConfig->tag(GenerateCommand::class, Command::class);

    $rectorConfig->singleton(InitRecipeCommand::class);
    $rectorConfig->tag(InitRecipeCommand::class, Command::class);

    // php-parser
    $rectorConfig->singleton(Standard::class, function () {
        return new Standard([
            'shortArraySyntax' => true,
        ]);
    });
};
