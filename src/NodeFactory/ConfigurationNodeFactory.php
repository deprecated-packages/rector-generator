<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\NodeFactory;

use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassConst;
use PhpParser\Node\Stmt\Property;
use Stringy\Stringy;

final class ConfigurationNodeFactory
{
    /**
     * @var NodeFactory
     */
    private $nodeFactory;

    public function __construct(NodeFactory $nodeFactory)
    {
        $this->nodeFactory = $nodeFactory;
    }

    /**
     * @param array<string, mixed> $ruleConfiguration
     * @return Property[]
     */
    public function createProperties(array $ruleConfiguration): array
    {
        $properties = [];

        foreach (array_keys($ruleConfiguration) as $constantName) {
            $stringy = new Stringy($constantName);
            $propertyName = (string) $stringy->toLowerCase()->camelize();

            $property = $this->nodeFactory->createPrivateArrayProperty($propertyName);
            $property->props[0]->default = new Array_([]);
            $properties[] = $property;
        }

        return $properties;
    }

    /**
     * @param array<string, mixed> $ruleConfiguration
     * @return ClassConst[]
     */
    public function createConfigurationConstants(array $ruleConfiguration): array
    {
        $classConsts = [];

        foreach (array_keys($ruleConfiguration) as $constantName) {
            $constantName = strtoupper($constantName);
            $constantValue = strtolower($constantName);
            $classConst = $this->nodeFactory->createPublicClassConst($constantName, $constantValue);
            $classConsts[] = $classConst;
        }

        return $classConsts;
    }
}
