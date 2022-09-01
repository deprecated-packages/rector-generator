<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Guard;

use Rector\RectorGenerator\FileSystem\TemplateFileSystem;
use Rector\RectorGenerator\ValueObject\RectorRecipe;
use Symfony\Component\Console\Style\SymfonyStyle;

final class OverrideGuard
{
    public function __construct(
        private readonly SymfonyStyle $symfonyStyle,
        private readonly TemplateFileSystem $templateFileSystem
    ) {
    }

    /**
     * @param array<string, mixed> $templateVariables
     * @param string[] $templateFilePaths
     */
    public function isUnwantedOverride(
        array $templateFilePaths,
        array $templateVariables,
        RectorRecipe $rectorRecipe,
        string $targetDirectory
    ): bool {
        $message = sprintf('Files for "%s" rule already exist. Should we override them?', $rectorRecipe->getName());

        foreach ($templateFilePaths as $templateFilePath) {
            if (! $this->doesFileInfoAlreadyExist(
                $templateVariables,
                $rectorRecipe,
                $templateFilePath,
                $targetDirectory
            )) {
                continue;
            }

            return ! $this->symfonyStyle->confirm($message);
        }

        return false;
    }

    /**
     * @param array<string, string> $templateVariables
     */
    private function doesFileInfoAlreadyExist(
        array $templateVariables,
        RectorRecipe $rectorRecipe,
        string $templateFilePath,
        string $targetDirectory
    ): bool {
        $destination = $this->templateFileSystem->resolveDestination(
            $templateFilePath,
            $templateVariables,
            $rectorRecipe,
            $targetDirectory
        );

        return file_exists($destination);
    }
}
