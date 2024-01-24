<?php

declare(strict_types=1);

use PhpParser\Node\Expr\MethodCall;
use Rector\Config\RectorConfig;
use Rector\RectorGenerator\Provider\RectorRecipeProvider;

return static function (RectorConfig $rectorConfig): void {
    // code before change - used for docs and a test fixture
    $codeBefore = <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $this->something();
    }
}
CODE_SAMPLE
    ;

    // code after change
    $codeAfter = <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $this->somethingElse();
    }
}
CODE_SAMPLE
    ;

    $rectorConfig->singleton(RectorRecipeProvider::class, function () use (
        $codeBefore,
        $codeAfter
    ): RectorRecipeProvider {
        return new RectorRecipeProvider(
            // package name, basically namespace part in `rules/<package>/src`, use PascalCase
            package: 'Naming',

            // name, basically short class name; use PascalCase
            name: 'RenameMethodCallRector',

            // 1+ node types to change, pick from classes here https://github.com/nikic/PHP-Parser/tree/master/lib/PhpParser/Node
            // the best practise is to have just 1 type here if possible, and make separated rule for other node types
            nodeTypes: [MethodCall::class],

            // describe what the rule does
            description: '"something()" will be renamed to "somethingElse()"',
            codeBefore: $codeBefore,
            codeAfter: $codeAfter,
        );
    });
};
