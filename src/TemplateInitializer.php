<?php

declare(strict_types=1);

namespace Rector\RectorGenerator;

use Symfony\Component\Console\Style\SymfonyStyle;
use Symplify\SmartFileSystem\FileSystemGuard;
use Symplify\SmartFileSystem\SmartFileSystem;

final class TemplateInitializer
{
    public function __construct(
        private readonly SymfonyStyle $symfonyStyle,
        private readonly SmartFileSystem $smartFileSystem,
        private readonly FileSystemGuard $fileSystemGuard
    ) {
    }

    public function initialize(string $templateFilePath, string $rootFileName): void
    {
        $this->fileSystemGuard->ensureFileExists($templateFilePath, __METHOD__);

        $targetFilePath = getcwd() . '/' . $rootFileName;

        $doesFileExist = $this->smartFileSystem->exists($targetFilePath);
        if ($doesFileExist) {
            $message = sprintf('Config file "%s" already exists', $rootFileName);
            $this->symfonyStyle->warning($message);
        } else {
            $this->smartFileSystem->copy($templateFilePath, $targetFilePath);
            $message = sprintf('"%s" config file was added', $rootFileName);
            $this->symfonyStyle->success($message);
        }
    }
}
