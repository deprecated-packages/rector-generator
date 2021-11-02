<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Tests\HttpKernel;

use Psr\Container\ContainerInterface;
use Symplify\SymplifyKernel\HttpKernel\AbstractSymplifyKernel;

final class DummyKernel extends AbstractSymplifyKernel
{
    /**
     * @param string[] $configFiles
     */
    public function createFromConfigs(array $configFiles): ContainerInterface
    {
        $configFiles = [
            // for tests
            __DIR__ . '/../config/config_tests.php',
            __DIR__ . '/../../config/config.php',
        ];

        return $this->create([], [], $configFiles);
    }
}
