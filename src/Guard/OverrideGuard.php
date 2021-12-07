<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Guard;

use Rector\RectorGenerator\FileSystem\TemplateFileSystem;
use Rector\RectorGenerator\ValueObject\RectorRecipe;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\SmartFileSystem\SmartFileInfo;

final class OverrideGuard
{
    public function __construct(
        private readonly SymfonyStyle $symfonyStyle,
        private readonly TemplateFileSystem $templateFileSystem
    ) {
    }

    /**
     * @param array<string, mixed> $templateVariables
     * @param SmartFileInfo[] $templateFileInfos
     */
    public function isUnwantedOverride(
        array $templateFileInfos,
        array $templateVariables,
        RectorRecipe $rectorRecipe,
        string $targetDirectory
    ): bool {
        $message = sprintf('Files for "%s" rule already exist. Should we override them?', $rectorRecipe->getName());

        foreach ($templateFileInfos as $templateFileInfo) {
            if (! $this->doesFileInfoAlreadyExist(
                $templateVariables,
                $rectorRecipe,
                $templateFileInfo,
                $targetDirectory
            )) {
                continue;
            }

            return ! $this->symfonyStyle->confirm($message);
        }

        return false;
    }

    /**
     * @param array<string, mixed> $templateVariables
     */
    private function doesFileInfoAlreadyExist(
        array $templateVariables,
        RectorRecipe $rectorRecipe,
        SmartFileInfo $templateFileInfo,
        string $targetDirectory
    ): bool {
        $destination = $this->templateFileSystem->resolveDestination(
            $templateFileInfo,
            $templateVariables,
            $rectorRecipe,
            $targetDirectory
        );

        return file_exists($destination);
    }
}
