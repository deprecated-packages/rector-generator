<?php

declare(strict_types=1);

namespace Rector\RectorGenerator;

use PhpParser\PrettyPrinter\Standard;
use Rector\RectorGenerator\NodeFactory\NodeFactory;
use Rector\RectorGenerator\ValueObject\Placeholder;
use Rector\RectorGenerator\ValueObject\RectorRecipe;

final class TemplateVariablesFactory
{
    public function __construct(
        private readonly Standard $standard,
        private readonly NodeFactory $nodeFactory,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function createFromRectorRecipe(RectorRecipe $rectorRecipe): array
    {
        $data = [
            Placeholder::PACKAGE => $rectorRecipe->getPackage(),
            Placeholder::CATEGORY => $rectorRecipe->getCategory(),
            Placeholder::DESCRIPTION => $rectorRecipe->getDescription(),
            Placeholder::NAME => $rectorRecipe->getName(),
            Placeholder::CODE_BEFORE => trim($rectorRecipe->getCodeBefore()) . PHP_EOL,
            Placeholder::CODE_BEFORE_EXAMPLE => $this->createCodeForDefinition($rectorRecipe->getCodeBefore()),
            Placeholder::CODE_AFTER => trim($rectorRecipe->getCodeAfter()) . PHP_EOL,
            Placeholder::CODE_AFTER_EXAMPLE => $this->createCodeForDefinition($rectorRecipe->getCodeAfter()),
        ];

        $data['__NodeTypesPhp__'] = $this->createNodeTypePhp($rectorRecipe);
        $data['__NodeTypesDoc__'] = '\\' . implode('|\\', $rectorRecipe->getNodeTypes());

        return $data;
    }

    private function createCodeForDefinition(string $code): string
    {
        if (\str_contains($code, PHP_EOL)) {
            // multi lines
            return sprintf("<<<'CODE_SAMPLE'%s%s%sCODE_SAMPLE%s", PHP_EOL, $code, PHP_EOL, PHP_EOL);
        }

        // single line
        return "'" . str_replace("'", '"', $code) . "'";
    }

    private function createNodeTypePhp(RectorRecipe $rectorRecipe): string
    {
        $referencingClassConsts = [];
        foreach ($rectorRecipe->getNodeTypes() as $nodeType) {
            $referencingClassConsts[] = $this->nodeFactory->createClassConstReference($nodeType);
        }

        $array = $this->nodeFactory->createArray($referencingClassConsts);
        return $this->standard->prettyPrintExpr($array);
    }
}
