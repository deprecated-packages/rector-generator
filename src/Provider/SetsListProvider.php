<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Provider;

use Rector\RectorGenerator\ValueObject\Option;
use Rector\Set\Contract\SetListInterface;
use ReflectionClass;
use Symplify\PackageBuilder\Parameter\ParameterProvider;

final class SetsListProvider
{
    public function __construct(
        private ParameterProvider $parameterProvider
    ) {
    }

    /**
     * @return string[]
     */
    public function provide(): array
    {
        /** @var array<class-string<SetListInterface>> $setListClasses */
        $setListClasses = $this->parameterProvider->provideArrayParameter(Option::SET_LIST_CLASSES);

        $setListNames = [];

        foreach ($setListClasses as $setListClass) {
            $reflectionClass = new ReflectionClass($setListClass);
            $setListNames[] = [...$setListNames, ...array_keys($reflectionClass->getConstants())];
        }

        return $setListNames;
    }
}
