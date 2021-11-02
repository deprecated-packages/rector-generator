<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Generator;

use Rector\RectorGenerator\Finder\TemplateFinder;
use Rector\RectorGenerator\TemplateVariablesFactory;
use Rector\RectorGenerator\ValueObject\RectorRecipe;

/**
 * @see \Rector\RectorGenerator\Tests\RectorGenerator\RectorGeneratorTest
 */
final class RectorGenerator
{
    public function __construct(
        private TemplateFinder $templateFinder,
        private TemplateVariablesFactory $templateVariablesFactory,
        private FileGenerator $fileGenerator
    ) {
    }

    public function generate(RectorRecipe $rectorRecipe, string $destinationDirectory): void
    {
        // generate and compare
        $templateFileInfos = $this->templateFinder->find($rectorRecipe);

        $templateVariables = $this->templateVariablesFactory->createFromRectorRecipe($rectorRecipe);

        $this->fileGenerator->generateFiles(
            $templateFileInfos,
            $templateVariables,
            $rectorRecipe,
            $destinationDirectory
        );
    }
}
