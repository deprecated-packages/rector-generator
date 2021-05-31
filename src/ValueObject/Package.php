<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\ValueObject;

final class Package
{
    /**
     * @var string
     */
    public const UTILS = 'Utils';

    public function __construct(
        private string $srcNamespace,
        private string $testsNamespace,
        private string $srcDirectory,
        private string $testsDirectory
    ) {
    }

    public function getSrcNamespace(): string
    {
        return $this->srcNamespace;
    }

    public function getTestsNamespace(): string
    {
        return $this->testsNamespace;
    }

    public function getSrcDirectory(): string
    {
        return $this->srcDirectory;
    }

    public function getTestsDirectory(): string
    {
        return $this->testsDirectory;
    }
}
