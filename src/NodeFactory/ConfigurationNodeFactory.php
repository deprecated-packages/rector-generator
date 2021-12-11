<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\NodeFactory;

use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Stmt\Property;

final class ConfigurationNodeFactory
{
    public function __construct(
        private readonly NodeFactory $nodeFactory
    ) {
    }

    /**
     * @param array<string, mixed> $ruleConfiguration
     * @return Property[]
     */
    public function createProperties(array $ruleConfiguration): array
    {
        $properties = [];

        foreach (array_keys($ruleConfiguration) as $privatePropertyName) {
            $property = $this->nodeFactory->createPrivateArrayProperty($privatePropertyName);
            $property->props[0]->default = new Array_([]);
            $properties[] = $property;
        }

        return $properties;
    }
}
