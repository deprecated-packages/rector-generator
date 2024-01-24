<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Tests\RectorGenerator\Source;

use PhpParser\Node\Expr\MethodCall;
use PHPStan\Node\ClassMethod;
use Rector\RectorGenerator\Exception\ShouldNotHappenException;
use Rector\RectorGenerator\ValueObject\RectorRecipe;

/**
 * @api used in tests
 */
final class StaticRectorRecipeFactory
{
    public static function createRectorRecipe(): RectorRecipe
    {
        $rectorRecipe = new RectorRecipe(
            'Utils',
            'WhateverRector',
            [MethodCall::class],
            'Change $service->arg(...) to $service->call(...)',
    <<<'CODE_SAMPLE'
<?php

$result = [];
echo 'code before';
CODE_SAMPLE
            , <<<'CODE_SAMPLE'
<?php

$result = [];
echo 'code after';
CODE_SAMPLE
        );

        $rectorRecipe->setPackage('ModeratePackage');

        return $rectorRecipe;
    }
}
