<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Tests\Provider;

use Rector\RectorGenerator\Provider\PackageNamesProvider;
use Rector\RectorGenerator\Tests\HttpKernel\DummyKernel;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;

final class PackageNamesProviderTest extends AbstractKernelTestCase
{
    private PackageNamesProvider $packageNamesProvider;

    protected function setUp(): void
    {
        $this->bootKernel(DummyKernel::class);
        $this->packageNamesProvider = $this->getService(PackageNamesProvider::class);
    }

    public function test(): void
    {
        $packageNames = $this->packageNamesProvider->provide();
        $this->assertCount(1, $packageNames);

        $this->assertContains('Symfony', $packageNames);
    }
}
