<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Generator;

use Nette\Utils\Strings;
use Rector\RectorGenerator\FileSystem\TemplateFileSystem;
use Rector\RectorGenerator\TemplateFactory;
use Rector\RectorGenerator\ValueObject\RectorRecipe;
use Symplify\SmartFileSystem\SmartFileInfo;
use Symplify\SmartFileSystem\SmartFileSystem;

final class FileGenerator
{
    /**
     * @var string
     * @see https://regex101.com/r/RVbPEX/1
     */
    public final const RECTOR_UTILS_REGEX = '#Rector\\\\Utils#';

    /**
     * @var string
     * @see https://regex101.com/r/RVbPEX/1
     */
    public final const RECTOR_UTILS_TESTS_REGEX = '#Rector\\\\Tests\\\\Utils#';

    public function __construct(
        private readonly SmartFileSystem $smartFileSystem,
        private readonly TemplateFactory $templateFactory,
        private readonly TemplateFileSystem $templateFileSystem
    ) {
    }

    /**
     * @param SmartFileInfo[] $templateFileInfos
     * @param string[] $templateVariables
     * @return string[]
     */
    public function generateFiles(
        array $templateFileInfos,
        array $templateVariables,
        RectorRecipe $rectorRecipe,
        string $destinationDirectory
    ): array {
        $generatedFilePaths = [];

        foreach ($templateFileInfos as $templateFileInfo) {
            $generatedFilePaths[] = $this->generateFileInfoWithTemplateVariables(
                $templateFileInfo,
                $templateVariables,
                $rectorRecipe,
                $destinationDirectory
            );
        }

        return $generatedFilePaths;
    }

    /**
     * @param array<string, mixed> $templateVariables
     */
    private function generateFileInfoWithTemplateVariables(
        SmartFileInfo $smartFileInfo,
        array $templateVariables,
        RectorRecipe $rectorRecipe,
        string $targetDirectory
    ): string {
        $targetFilePath = $this->templateFileSystem->resolveDestination(
            $smartFileInfo,
            $templateVariables,
            $rectorRecipe,
            $targetDirectory
        );

        $content = $this->templateFactory->create($smartFileInfo->getContents(), $templateVariables);

        // replace "Rector\Utils\" with "Utils\Rector\" for 3rd party packages
        if (! $rectorRecipe->isRectorRepository()) {
            $content = Strings::replace($content, self::RECTOR_UTILS_REGEX, 'Utils\Rector');
            $content = Strings::replace($content, self::RECTOR_UTILS_TESTS_REGEX, 'Utils\Rector\Tests');
        }

        $this->smartFileSystem->dumpFile($targetFilePath, $content);

        return $targetFilePath;
    }
}
