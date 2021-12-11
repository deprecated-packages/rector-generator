<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Tests\RectorGenerator\Source;

use PhpParser\Node\Expr\MethodCall;
use Rector\RectorGenerator\Exception\ShouldNotHappenException;
use Rector\RectorGenerator\ValueObject\RectorRecipe;

final class StaticRectorRecipeFactory
{
    public static function createRectorRecipe(string $setFilePath, bool $isRectorRepository): RectorRecipe
    {
        if (! file_exists($setFilePath)) {
            $message = sprintf('Set file path "%s" was not found', $setFilePath);
            throw new ShouldNotHappenException($message);
        }

        $rectorRecipe = new RectorRecipe(
            'Utils',
            'WhateverRector',
            [MethodCall::class],
            'Change $service->arg(...) to $service->call(...)',
    <<<'CODE_SAMPLE'
<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(SomeClass::class)
        ->arg('$key', 'value');
}
CODE_SAMPLE
            , <<<'CODE_SAMPLE'
<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(SomeClass::class)
        ->call('configure', [[
            '$key' => 'value'
        ]]);
}
CODE_SAMPLE
        );

        $rectorRecipe->setConfiguration([
            'SomeClass' => 'configure'
        ]);

        $rectorRecipe->setIsRectorRepository($isRectorRepository);
        if ($isRectorRepository) {
            $rectorRecipe->setPackage('ModeratePackage');
        }

        $rectorRecipe->setSetFilePath($setFilePath);

        return $rectorRecipe;
    }
}
