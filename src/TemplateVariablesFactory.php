<?php

declare(strict_types=1);

namespace Rector\RectorGenerator;

use PhpParser\BuilderHelpers;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ArrayItem;
use PhpParser\PrettyPrinter\Standard;
use Rector\RectorGenerator\NodeFactory\ConfigurationNodeFactory;
use Rector\RectorGenerator\NodeFactory\ConfigureClassMethodFactory;
use Rector\RectorGenerator\NodeFactory\NodeFactory;
use Rector\RectorGenerator\ValueObject\Placeholder;
use Rector\RectorGenerator\ValueObject\RectorRecipe;

final class TemplateVariablesFactory
{
    public function __construct(
        private readonly Standard $standard,
        private readonly ConfigurationNodeFactory $configurationNodeFactory,
        private readonly ConfigureClassMethodFactory $configureClassMethodFactory,
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

        if ($rectorRecipe->getConfiguration() !== []) {
            $configurationData = $this->createConfigurationData($rectorRecipe);
            $data = array_merge($data, $configurationData);
        }

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

    /**
     * @param string[] $source
     */
    private function createSourceDocBlock(array $source): string
    {
        if ($source === []) {
            return '';
        }

        $sourceAsString = '';
        foreach ($source as $singleSource) {
            $sourceAsString .= ' * @changelog ' . $singleSource . PHP_EOL;
        }

        $sourceAsString .= ' *';

        return rtrim($sourceAsString);
    }

    /**
     * @param array<string, mixed> $configuration
     */
    private function createRuleConfiguration(array $configuration): string
    {
        $arrayItems = [];
        foreach ($configuration as $singleConfiguration) {
            $singleConfiguration = BuilderHelpers::normalizeValue($singleConfiguration);
            $arrayItems[] = new ArrayItem($singleConfiguration);
        }

        $array = new Array_($arrayItems);
        return $this->standard->prettyPrintExpr($array);
    }

    /**
     * @param array<string, mixed> $ruleConfiguration
     */
    private function createConfigurationProperty(array $ruleConfiguration): string
    {
        $properties = $this->configurationNodeFactory->createProperties($ruleConfiguration);
        return $this->standard->prettyPrint($properties);
    }

    /**
     * @param array<string, mixed> $ruleConfiguration
     */
    private function createConfigureClassMethod(array $ruleConfiguration): string
    {
        $classMethod = $this->configureClassMethodFactory->create($ruleConfiguration);
        return $this->standard->prettyPrint([$classMethod]);
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

    /**
     * @return array<string, mixed>
     */
    private function createConfigurationData(RectorRecipe $rectorRecipe): array
    {
        $configurationData = [];

        $configurationData['__TestRuleConfiguration__'] = $this->createRuleConfiguration(
            $rectorRecipe->getConfiguration()
        );

        $configurationData['__RuleConfiguration__'] = $this->createRuleConfiguration(
            $rectorRecipe->getConfiguration()
        );

        $configurationData['__ConfigurationProperties__'] = $this->createConfigurationProperty(
            $rectorRecipe->getConfiguration()
        );

        $configurationData['__ConfigureClassMethod__'] = $this->createConfigureClassMethod(
            $rectorRecipe->getConfiguration()
        );

        $configurationData['__MainConfiguration__'] = $this->createMainConfiguration(
            $rectorRecipe->getConfiguration()
        );

        return $configurationData;
    }

    /**
     * @param array<string, mixed> $ruleConfiguration
     */
    private function createMainConfiguration(array $ruleConfiguration): string
    {
        $firstItem = array_pop($ruleConfiguration);

        $valueExpr = BuilderHelpers::normalizeValue($firstItem);
        return $this->standard->prettyPrintExpr($valueExpr);
    }
}
