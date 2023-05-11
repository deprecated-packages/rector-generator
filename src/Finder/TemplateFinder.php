<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Finder;

use Rector\RectorGenerator\ValueObject\RectorRecipe;
use Webmozart\Assert\Assert;

final class TemplateFinder
{
    /**
     * @var string
     */
    public const TEMPLATES_DIRECTORY = __DIR__ . '/../../templates';

    /**
     * @return string[]
     */
    public function find(RectorRecipe $rectorRecipe): array
    {
        $filePaths = [];

        $filePaths = $this->addRuleAndTestCase($rectorRecipe, $filePaths);
        $filePaths[] = __DIR__ . '/../../templates/rules-tests/__Package__/Rector/__Category__/__Name__/Fixture/some_class.php.inc';

        $this->ensureFilePathsExists($filePaths);

        return $filePaths;
    }

    /**
     * @param string[] $filePaths
     * @return string[]
     *
     * @note the ".inc" suffix is needed, so PHPUnit doesn't load it as a test case
     */
    private function addRuleAndTestCase(RectorRecipe $rectorRecipe, array $filePaths): array
    {
        if ($rectorRecipe->getConfiguration() !== []) {
            $filePaths[] = __DIR__ . '/../../templates/rules-tests/__Package__/Rector/__Category__/__Name__/config/__Configuredconfigured_rule.php';
        } else {
            $filePaths[] = __DIR__ . '/../../templates/rules-tests/__Package__/Rector/__Category__/__Name__/config/configured_rule.php';
        }

        $filePaths[] = __DIR__ . '/../../templates/rules-tests/__Package__/Rector/__Category__/__Name__/__Name__Test.php.inc';

        if ($rectorRecipe->getConfiguration() !== []) {
            $filePaths[] = __DIR__ . '/../../templates/rules/__Package__/Rector/__Category__/__Configured__Name__.php';
        } else {
            $filePaths[] = __DIR__ . '/../../templates/rules/__Package__/Rector/__Category__/__Name__.php';
        }

        return $filePaths;
    }

    /**
     * @param string[] $filePaths
     */
    private function ensureFilePathsExists(array $filePaths): void
    {
        Assert::allFileExists($filePaths, __METHOD__);
    }
}
