<?php

declare(strict_types=1);

use PhpParser\Node\Expr\MethodCall;
use Rector\RectorGenerator\Provider\RectorRecipeProvider;
use Rector\RectorGenerator\ValueObject\Option;

// run "bin/rector generate" to a new Rector basic schema + tests from this config
return static function (\Rector\Config\RectorConfig $rectorConfig): void {
    // [REQUIRED]

    $rectorRecipeConfiguration = [
        // [RECTOR CORE CONTRIBUTION - REQUIRED]
        // package name, basically namespace part in `rules/<package>/src`, use PascalCase
        Option::PACKAGE => 'Naming',

        // name, basically short class name; use PascalCase
        Option::NAME => 'RenameMethodCallRector',

        // 1+ node types to change, pick from classes here https://github.com/nikic/PHP-Parser/tree/master/lib/PhpParser/Node
        // the best practise is to have just 1 type here if possible, and make separated rule for other node types
        Option::NODE_TYPES => [MethodCall::class],

        // describe what the rule does
        Option::DESCRIPTION => '"something()" will be renamed to "somethingElse()"',

        // code before change
        // this is used for documentation and first test fixture
        Option::CODE_BEFORE => <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $this->something();
    }
}
CODE_SAMPLE
        ,
        // code after change
        Option::CODE_AFTER => <<<'CODE_SAMPLE'
class SomeClass
{
    public function run()
    {
        $this->somethingElse();
    }
}
CODE_SAMPLE
        ,

        // [OPTIONAL - UNCOMMENT TO USE]

        // links to useful websites, that explain why the change is needed
        //        Option::RESOURCES => [
        //            'https://github.com/symfony/symfony/blob/6.1/UPGRADE-6.0.md'
        //        ],

        // is the rule configurable?
        //        Option::CONFIGURATION => [
        //            'privatePropertyName' => [
        //                'old_value' => 'newValue',
        //            ],
        //        ],

        // set the rule belongs to; is optional, because e.g. generic rules don't need a specific set to belong to
        // Option::SET_FILE_PATH => \Rector\Set\ValueObject\SetList::NAMING,
    ];

    $rectorConfig->singleton(RectorRecipeProvider::class, function () use (
        $rectorRecipeConfiguration
    ): RectorRecipeProvider {
        return new RectorRecipeProvider($rectorRecipeConfiguration);
    });
};
