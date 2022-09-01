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
    /**
     * @var string
     * @see https://regex101.com/r/RVbPEX/1
     */
    public const RECTOR_UTILS_REGEX = '#Rector\\\\Utils#';

    /**
     * @var string
     * @see https://regex101.com/r/RVbPEX/1
     */
    public const RECTOR_UTILS_TESTS_REGEX = '#Rector\\\\Tests\\\\Utils#';

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

        // replace "Rector\Utils\" with "Utils\Rector\" for 3rd party packages
        if (! $rectorRecipe->isRectorRepository()) {
            $content = Strings::replace($content, self::RECTOR_UTILS_REGEX, 'Utils\Rector');
            $content = Strings::replace($content, self::RECTOR_UTILS_TESTS_REGEX, 'Utils\Rector\Tests');
        }

        // correct tests PSR-4 namespace for core rector packages
        if (in_array($rectorRecipe->getPackage(), Packages::RECTOR_CORE, true)) {
            $content = Strings::replace(
                $content,
                '#namespace Rector\\\\Tests\\\\' . $rectorRecipe->getPackage() . '#',
                'namespace Rector\\' . $rectorRecipe->getPackage() . '\\Tests',
            );

            // add core package main config
            if (str_ends_with($targetFilePath, 'configured_rule.php')) {
                $rectorConfigLine = 'return static function (RectorConfig $rectorConfig): void {';

                $content = str_replace(
                    $rectorConfigLine,
                    $rectorConfigLine . PHP_EOL . '    $rectorConfig->import(__DIR__ . \'/../../../../../config/config.php\');' . PHP_EOL,
                    $content
                );
            }
        }

        $this->filesystem->dumpFile($targetFilePath, $content);

        return $targetFilePath;
    }
}
