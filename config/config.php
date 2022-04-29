<?php

declare(strict_types=1);

use PhpParser\Parser;
use PhpParser\ParserFactory;
use PhpParser\PrettyPrinter\Standard;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use Symplify\PackageBuilder\Console\Style\SymfonyStyleFactory;
use Symplify\PackageBuilder\Parameter\ParameterProvider;
use Symplify\PackageBuilder\Reflection\PrivatesAccessor;
use Symplify\PackageBuilder\Reflection\PrivatesCaller;
use Symplify\SmartFileSystem\FileSystemGuard;
use Symplify\SmartFileSystem\Finder\FinderSanitizer;
use Symplify\SmartFileSystem\Json\JsonFileSystem;
use Symplify\SmartFileSystem\SmartFileSystem;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->public()
        ->autowire()
        ->autoconfigure();

    $services->load('Rector\\RectorGenerator\\', __DIR__ . '/../src')
        ->exclude([__DIR__ . '/../src/ValueObject']);

    $services->set(ParameterProvider::class)
        ->arg('$container', service('service_container'));

    // console
    $services->set(SymfonyStyleFactory::class);
    $services->set(SymfonyStyle::class)
        ->factory([service(SymfonyStyleFactory::class), 'create']);

    // filesystem
    $services->set(JsonFileSystem::class);
    $services->set(SmartFileSystem::class);
    $services->set(FinderSanitizer::class);
    $services->set(FileSystemGuard::class);

    // privates
    $services->set(PrivatesCaller::class);
    $services->set(PrivatesAccessor::class);

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
