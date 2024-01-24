<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Finder;

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
    public function find(): array
    {
        $filePaths = [
            __DIR__ . '/../../templates/rules-tests/__Package__/Rector/__Category__/__Name__/config/configured_rule.php',
            // the ".inc" suffix is needed, so PHPUnit doesn't load it as a test case
            __DIR__ . '/../../templates/rules-tests/__Package__/Rector/__Category__/__Name__/__Name__Test.php.inc',
            __DIR__ . '/../../templates/rules/__Package__/Rector/__Category__/__Name__.php',
            __DIR__ . '/../../templates/rules-tests/__Package__/Rector/__Category__/__Name__/Fixture/some_class.php.inc',
        ];

        Assert::allFileExists($filePaths);

        return $filePaths;
    }
}
