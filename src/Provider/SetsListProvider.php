<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Provider;

use Rector\RectorGenerator\ValueObject\Option;
use ReflectionClass;
use Symplify\PackageBuilder\Parameter\ParameterProvider;

final class SetsListProvider
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
        /** @var array<class-string<\Rector\Set\Contract\SetListInterface>> $setListClasses */
        $setListClasses = $this->parameterProvider->provideArrayParameter(Option::SET_LIST_CLASSES);

        $setListNames = [];

        foreach ($setListClasses as $setListClass) {
            $reflectionClass = new ReflectionClass($setListClass);
            $setListNames[] = array_merge($setListNames, array_keys($reflectionClass->getConstants()));
        }

        return $setListNames;
    }
}
