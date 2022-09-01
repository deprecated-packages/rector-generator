<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Generator;

use Rector\RectorGenerator\Finder\TemplateFinder;
use Rector\RectorGenerator\Guard\OverrideGuard;
use Rector\RectorGenerator\TemplateVariablesFactory;
use Rector\RectorGenerator\ValueObject\RectorRecipe;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @see \Rector\RectorGenerator\Tests\RectorGenerator\RectorGeneratorTest
 */
final class RectorGenerator
{
    public function __construct(
        private readonly TemplateFinder $templateFinder,
        private readonly TemplateVariablesFactory $templateVariablesFactory,
        private readonly FileGenerator $fileGenerator,
        private readonly OverrideGuard $overrideGuard,
        private readonly SymfonyStyle $symfonyStyle,
    ) {
    }

    /**
     * @return string[]
     */
    public function generate(RectorRecipe $rectorRecipe, string $destinationDirectory): array
    {
        // generate and compare
        $templateFilePaths = $this->templateFinder->find($rectorRecipe);

        $templateVariables = $this->templateVariablesFactory->createFromRectorRecipe($rectorRecipe);

        $isUnwantedOverride = $this->overrideGuard->isUnwantedOverride(
            $templateFilePaths,
            $templateVariables,
            $rectorRecipe,
            $destinationDirectory
        );

        if ($isUnwantedOverride) {
            $this->symfonyStyle->warning('No files were changed');
            return [];
        }

        return $this->fileGenerator->generateFiles(
            $templateFilePaths,
            $templateVariables,
            $rectorRecipe,
            $destinationDirectory
        );
    }
}
