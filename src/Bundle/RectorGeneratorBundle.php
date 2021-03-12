<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Bundle;

use Rector\RectorGenerator\DependencyInjection\Extension\RectorGeneratorExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class RectorGeneratorBundle extends Bundle
{
    protected function createContainerExtension(): RectorGeneratorExtension
    {
        return new RectorGeneratorExtension();
    }
}
