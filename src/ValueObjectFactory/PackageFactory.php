<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\ValueObjectFactory;

use Rector\RectorGenerator\ValueObject\Package;
use Rector\RectorGenerator\ValueObject\RectorRecipe;

final class PackageFactory
{
    public function create(RectorRecipe $rectorRecipe): Package
    {
        if (! $rectorRecipe->isRectorRepository()) {
            return new Package(
                'Utils\\Rector\\',
                'Utils\\Rector\\Tests\\',
                'utils/rector/src',
                'utils/rector/tests'
            );
        }

        return new Package(
            'Rector\\' . $rectorRecipe->getPackage() . '\\',
            'Rector\\Tests\\' . $rectorRecipe->getPackage(),
            'rules/' . $rectorRecipe->getPackage(),
            'rules-tests/' . $rectorRecipe->getPackage()
        );
    }
}
