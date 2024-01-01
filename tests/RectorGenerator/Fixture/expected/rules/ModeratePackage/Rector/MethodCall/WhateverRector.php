<?php

declare(strict_types=1);

namespace Rector\ModeratePackage\Rector\MethodCall;

use PhpParser\Node;
use Rector\Contract\Rector\ConfigurableRectorInterface;
use Rector\Rector\AbstractRector;
use Symplify\RuleDocGenerator\ValueObject\CodeSample\ConfiguredCodeSample;
use Symplify\RuleDocGenerator\ValueObject\RuleDefinition;

/**

 * @see \Rector\Tests\ModeratePackage\Rector\MethodCall\WhateverRector\WhateverRectorTest
 */
final class WhateverRector extends AbstractRector implements ConfigurableRectorInterface
{
    /**
 * @var mixed[]
 */
private $renamedPackages = [];

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Change $service->arg(...) to $service->call(...)', [
            new ConfiguredCodeSample(
                <<<'CODE_SAMPLE'
$result = [];
echo 'code before';
CODE_SAMPLE
,
                <<<'CODE_SAMPLE'
$result = [];
echo 'code after';
CODE_SAMPLE
,
                [['old_package_name' => 'new_package_name']]
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

    /**
 * @param mixed[] $configuration
 */
public function configure(array $configuration) : void
{
    $this->renamedPackages = $configuration;
}
}
