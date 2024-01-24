<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Generator;

use Nette\Utils\Strings;
use Rector\RectorGenerator\Enum\Packages;
use Rector\RectorGenerator\FileSystem\TemplateFileSystem;
use Rector\RectorGenerator\TemplateFactory;
use Rector\RectorGenerator\ValueObject\RectorRecipe;
use Symfony\Component\Filesystem\Filesystem;

final class FileGenerator
{
    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly TemplateFactory $templateFactory,
        private readonly TemplateFileSystem $templateFileSystem
    ) {
    }

    /**
     * @param string[] $templateFilePaths
     * @param array<string, string> $templateVariables
     * @return string[]
     */
    public function generateFiles(
        array $templateFilePaths,
        array $templateVariables,
        RectorRecipe $rectorRecipe,
        string $destinationDirectory
    ): array {
        $generatedFilePaths = [];

        foreach ($templateFilePaths as $templateFilePath) {
            $generatedFilePaths[] = $this->generateFileInfoWithTemplateVariables(
                $templateFilePath,
                $templateVariables,
                $rectorRecipe,
                $destinationDirectory
            );
        }

        return $generatedFilePaths;
    }

    /**
     * @param array<string, string> $templateVariables
     */
    private function generateFileInfoWithTemplateVariables(
        string $templateFilePath,
        array $templateVariables,
        RectorRecipe $rectorRecipe,
        string $targetDirectory
    ): string {
        $targetFilePath = $this->templateFileSystem->resolveDestination(
            $templateFilePath,
            $templateVariables,
            $rectorRecipe,
            $targetDirectory
        );

        $templateFileContents = \Nette\Utils\FileSystem::read($templateFilePath);

        $content = $this->templateFactory->create($templateFileContents, $templateVariables);

        // correct tests PSR-4 namespace for core rector packages
        if (in_array($rectorRecipe->getPackage(), Packages::RECTOR_CORE, true)) {
            $content = Strings::replace(
                $content,
                '#namespace Rector\\\\Tests\\\\' . $rectorRecipe->getPackage() . '#',
                'namespace Rector\\' . $rectorRecipe->getPackage() . '\\Tests',
            );
        }

        $this->filesystem->dumpFile($targetFilePath, $content);

        return $targetFilePath;
    }
}
