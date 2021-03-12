<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\NodeFactory;

use PhpParser\Comment\Doc;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayDimFetch;
use PhpParser\Node\Expr\BinaryOp\Coalesce;
use PhpParser\Node\Expr\ClassConstFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use Rector\RectorGenerator\Utils\StringTransformator;

final class ConfigureClassMethodFactory
{
    /**
     * @var NodeFactory
     */
    private $nodeFactory;

    /**
     * @var StringTransformator
     */
    private $stringTransformator;

    public function __construct(
        NodeFactory $nodeFactory,
        StringTransformator $stringTransformator
    ) {
        $this->nodeFactory = $nodeFactory;
        $this->stringTransformator = $stringTransformator;
    }

    /**
     * @param array<string, mixed> $ruleConfiguration
     */
    public function create(array $ruleConfiguration): ClassMethod
    {
        $classMethod = $this->nodeFactory->createPublicMethod('configure');
        $classMethod->returnType = new Identifier('void');

        $configurationVariable = new Variable('configuration');
        $configurationParam = new Param($configurationVariable);
        $configurationParam->type = new Identifier('array');
        $classMethod->params[] = $configurationParam;

        $assigns = [];
        foreach (array_keys($ruleConfiguration) as $constantName) {
            $coalesce = $this->createConstantInConfigurationCoalesce($constantName, $configurationVariable);

            $propertyName = $this->stringTransformator->uppercaseUnderscoreToCamelCase($constantName);
            $assign = $this->nodeFactory->createPropertyAssign($propertyName, $coalesce);
            $assigns[] = new Expression($assign);
        }

        $classMethod->stmts = $assigns;

        $paramDoc = <<<'CODE_SAMPLE'
/**
 * @param array<string, mixed> $configuration
 */
CODE_SAMPLE;

        $classMethod->setDocComment(new Doc($paramDoc));

        return $classMethod;
    }

    private function createConstantInConfigurationCoalesce(
        string $constantName,
        Variable $configurationVariable
    ): Coalesce {
        $constantName = strtoupper($constantName);

        $classConstFetch = new ClassConstFetch(new Name('self'), $constantName);
        $arrayDimFetch = new ArrayDimFetch($configurationVariable, $classConstFetch);

        $emptyArray = new Array_([]);

        return new Coalesce($arrayDimFetch, $emptyArray);
    }
}
