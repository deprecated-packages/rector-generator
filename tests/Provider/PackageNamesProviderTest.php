<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Tests\Provider;

use Rector\RectorGenerator\Provider\PackageNamesProvider;
use Rector\RectorGenerator\Tests\HttpKernel\DummyKernel;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;

final class PackageNamesProviderTest extends AbstractKernelTestCase
{
    /**
     * @var PackageNamesProvider
     */
    private $packageNamesProvider;

    protected function setUp(): void
    {
        $this->bootKernel(DummyKernel::class);
        $this->packageNamesProvider = $this->getService(PackageNamesProvider::class);
    }

    public function test(): void
    {
        $packageNames = $this->packageNamesProvider->provide();
        $packageNameCount = count($packageNames);
        $this->assertGreaterThan(60, $packageNameCount);

        $this->assertContains('DeadCode', $packageNames);
        $this->assertContains('Symfony5', $packageNames);
    }
}
