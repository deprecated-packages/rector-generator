<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\FileSystem;

use Symplify\SmartFileSystem\SmartFileSystem;

final class PathHelper
{
    public static function getRelativePathFromDirector(string $filePath, string $directory): string
    {
        $smartFileSystem = new SmartFileSystem();

        $relativeFilePath = $smartFileSystem->makePathRelative($filePath, (string) realpath($directory));

        return rtrim($relativeFilePath, '/');
    }
}
