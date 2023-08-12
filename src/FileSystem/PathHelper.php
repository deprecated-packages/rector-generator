<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\FileSystem;

use Symfony\Component\Filesystem\Filesystem;

final class PathHelper
{
    public static function getRelativePathFromDirector(string $filePath, string $directory): string
    {
        $smartFileSystem = new Filesystem();
        $relativeFilePath = $smartFileSystem->makePathRelative($filePath, (string) realpath($directory));

        return rtrim($relativeFilePath, '/');
    }
}
