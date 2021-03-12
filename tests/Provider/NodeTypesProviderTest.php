<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Tests\Provider;

use Rector\RectorGenerator\Provider\NodeTypesProvider;
use Rector\RectorGenerator\Tests\HttpKernel\DummyKernel;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;

final class NodeTypesProviderTest extends AbstractKernelTestCase
{
    /**
     * @var NodeTypesProvider
     */
    private $nodeTypesProvider;

    protected function setUp(): void
    {
        $this->bootKernel(DummyKernel::class);
        $this->nodeTypesProvider = $this->getService(NodeTypesProvider::class);
    }

    public function test(): void
    {
        $nodeTypes = $this->nodeTypesProvider->provide();
        $nodeTypeCount = count($nodeTypes);
        $this->assertGreaterThan(70, $nodeTypeCount);

        $this->assertContains('Expr\New_', $nodeTypes);
        $this->assertContains('Param', $nodeTypes);
    }
}
