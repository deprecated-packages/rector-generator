<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Provider;

use Rector\RectorGenerator\Utils\StringTransformator;
use Rector\RectorGenerator\ValueObject\Option;
use SplFileInfo;
use Symfony\Component\Finder\Finder;
use Symplify\PackageBuilder\Parameter\ParameterProvider;

/**
 * @see \Rector\RectorGenerator\Tests\Provider\PackageNamesProviderTest
 */
final class PackageNamesProvider
{
    /**
     * @var StringTransformator
     */
    private $stringTransformator;

    /**
     * @var ParameterProvider
     */
    private $parameterProvider;

    public function __construct(StringTransformator $stringTransformator, ParameterProvider $parameterProvider)
    {
        $this->stringTransformator = $stringTransformator;
        $this->parameterProvider = $parameterProvider;
    }

    /**
     * @return string[]
     */
    public function provide(): array
    {
        $rulesDirectory = $this->parameterProvider->provideStringParameter(Option::RULES_DIRECTORY);

        $finder = new Finder();
        $finder = $finder->directories()
            ->depth(0)
            ->in($rulesDirectory)
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
