<?php

declare(strict_types=1);

use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;
use Rector\RectorGenerator\Command\GenerateCommand;
use Rector\RectorGenerator\Command\InitRecipeCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Filesystem\Filesystem;
use Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();

    $services->load('Rector\\RectorGenerator\\', __DIR__ . '/../src')
        ->exclude([__DIR__ . '/../src/ValueObject', __DIR__ . '/../src/Enum']);

    // console
    $services->set(SymfonyStyleFactory::class);
    $services->set(SymfonyStyle::class)
        ->factory([service(SymfonyStyleFactory::class), 'create']);

    $services->set(Filesystem::class);

    $services->set(Application::class)
        ->call('add', [service(GenerateCommand::class)])
        ->call('add', [service(InitRecipeCommand::class)]);

    // php-parser
    $services->set(Standard::class)
        ->arg('$options', [
            'shortArraySyntax' => true,
        ]);
    $services->set(ParserFactory::class);
    $services->set(Parser::class)
        ->factory([service(ParserFactory::class), 'create'])
        ->arg('$kind', ParserFactory::PREFER_PHP7);
};
