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
        private TemplateFinder $templateFinder,
        private TemplateVariablesFactory $templateVariablesFactory,
        private FileGenerator $fileGenerator,
        private OverrideGuard $overrideGuard,
        private SymfonyStyle $symfonyStyle,
    ) {
    }

    /**
     * @return string[]
     */
    public function generate(RectorRecipe $rectorRecipe, string $destinationDirectory): array
    {
        // generate and compare
        $templateFileInfos = $this->templateFinder->find($rectorRecipe);

        $templateVariables = $this->templateVariablesFactory->createFromRectorRecipe($rectorRecipe);

        $isUnwantedOverride = $this->overrideGuard->isUnwantedOverride(
            $templateFileInfos,
            $templateVariables,
            $rectorRecipe,
            $destinationDirectory
        );

        if ($isUnwantedOverride) {
            $this->symfonyStyle->warning('No files were changed');
            return [];
        }

        return $this->fileGenerator->generateFiles(
            $templateFileInfos,
            $templateVariables,
            $rectorRecipe,
            $destinationDirectory
        );
    }
}
