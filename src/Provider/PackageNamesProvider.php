<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Provider;

use Rector\RectorGenerator\ValueObject\Option;
use SplFileInfo;
use Stringy\Stringy;
use Symfony\Component\Finder\Finder;
use Symplify\PackageBuilder\Parameter\ParameterProvider;

/**
 * @see \Rector\RectorGenerator\Tests\Provider\PackageNamesProviderTest
 */
final class PackageNamesProvider
{
    /**
     * @var ParameterProvider
     */
    private $parameterProvider;

    public function __construct(ParameterProvider $parameterProvider)
    {
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
            $stringy = new Stringy($fileInfo->getFilename());
            $packageNames[] = (string) $stringy->upperCamelize();
        }

        return $packageNames;
    }
}
