<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\FileSystem;

use Symfony\Component\Filesystem\Filesystem;

final class PathHelper
{
    public static function getRelativePathFromDirectory(string $filePath, string $directory): string
    {
        $filesystem = new Filesystem();
        $relativeFilePath = $filesystem->makePathRelative($filePath, (string) realpath($directory));

        return rtrim($relativeFilePath, '/');
    }
}
