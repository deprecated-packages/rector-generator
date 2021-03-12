<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Provider;

use Rector\RectorGenerator\Utils\StringTransformator;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

/**
 * @see \Rector\RectorGenerator\Tests\Provider\PackageNamesProviderTest
 */
final class PackageNamesProvider
{
    /**
     * @var StringTransformator
     */
    private $stringTransformator;

    public function __construct(StringTransformator $stringTransformator)
    {
        $this->stringTransformator = $stringTransformator;
    }

    /**
     * @return string[]
     */
    public function provide(): array
    {
        $finder = new Finder();
        $finder = $finder->directories()
            ->depth(0)
            ->in(__DIR__ . '/../../../../rules')
            ->sortByName();

        $fileInfos = iterator_to_array($finder->getIterator());

        $packageNames = [];

        foreach ($fileInfos as $fileInfo) {
            /** @var SplFileInfo $fileInfo */
            $packageNames[] = $this->stringTransformator->dashesToCamelCase($fileInfo->getFilename());
        }

        return $packageNames;
    }
}
