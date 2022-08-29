<?php

declare(strict_types=1);

namespace Rector\RectorGenerator;

use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Webmozart\Assert\Assert;

final class TemplateInitializer
{
    public function __construct(
        private readonly SymfonyStyle $symfonyStyle,
        private readonly Filesystem $filesystem,
    ) {
    }

    public function initialize(string $templateFilePath, string $rootFileName): void
    {
        Assert::fileExists($templateFilePath, __METHOD__);

        $targetFilePath = getcwd() . '/' . $rootFileName;

        $doesFileExist = $this->filesystem->exists($targetFilePath);
        if ($doesFileExist) {
            $message = sprintf('Config file "%s" already exists', $rootFileName);
            $this->symfonyStyle->warning($message);
        } else {
            $this->filesystem->copy($templateFilePath, $targetFilePath);
            $message = sprintf('"%s" config file was added', $rootFileName);
            $this->symfonyStyle->success($message);
        }
    }
}
