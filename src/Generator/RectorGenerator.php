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
        private readonly TemplateFinder $templateFinder,
        private readonly TemplateVariablesFactory $templateVariablesFactory,
        private readonly FileGenerator $fileGenerator,
    ) {
    }

    /**
     * @return string[]
     */
    public function generate(RectorRecipe $rectorRecipe, string $destinationDirectory): array
    {
        // generate and compare
        $templateFilePaths = $this->templateFinder->find();
        $templateVariables = $this->templateVariablesFactory->createFromRectorRecipe($rectorRecipe);

        return $this->fileGenerator->generateFiles(
            $templateFilePaths,
            $templateVariables,
            $rectorRecipe,
            $destinationDirectory
        );
    }
}
