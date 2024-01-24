<?php

declare(strict_types=1);

namespace Rector\ModeratePackage\Rector\MethodCall;

use PhpParser\Node;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\CodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**
 * @see \Rector\Tests\ModeratePackage\Rector\MethodCall\WhateverRector\WhateverRectorTest
 */
final class WhateverRector extends AbstractRector
{
    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change $service->arg(...) to $service->call(...)', [
            new CodeSample(
                <<<'CODE_SAMPLE'
$result = [];
echo 'code before';
CODE_SAMPLE

                ,
                <<<'CODE_SAMPLE'
$result = [];
echo 'code after';
CODE_SAMPLE

            )
        ]);
    }

    /**
     * @return array<class-string<Node>>
     */
    public function getNodeTypes(): array
    {
        return [\PhpParser\Node\Expr\MethodCall::class];
    }

    /**
     * @param \PhpParser\Node\Expr\MethodCall $node
     */
    public function refactor(Node $node): ?Node
    {
        // change the node

        return $node;
    }
}
