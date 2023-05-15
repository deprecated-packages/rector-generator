<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Tests\RectorGenerator;

use Nette\Utils\FileSystem;
use Rector\RectorGenerator\Generator\RectorGenerator;
use Rector\RectorGenerator\Tests\HttpKernel\DummyKernel;
use Rector\RectorGenerator\Tests\RectorGenerator\Source\StaticRectorRecipeFactory;
use Rector\RectorGenerator\ValueObject\RectorRecipe;
use Symplify\EasyTesting\PHPUnit\Behavior\DirectoryAssertableTrait;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;

final class RectorGeneratorTest extends AbstractKernelTestCase
{
    use DirectoryAssertableTrait;

    /**
     * @var string
     */
    private const DESTINATION_DIRECTORY = __DIR__ . '/__temp';

    private RectorGenerator $rectorGenerator;

    protected function setUp(): void
    {
        $this->bootKernel(DummyKernel::class);

        $this->rectorGenerator = $this->getService(RectorGenerator::class);
    }

    protected function tearDown(): void
    {
        // cleanup temporary data
        FileSystem::delete(self::DESTINATION_DIRECTORY);
    }

    public function test(): void
    {
        $rectorRecipe = $this->createConfiguration(__DIR__ . '/Source/config/some_set.php', true);
        $this->rectorGenerator->generate($rectorRecipe, self::DESTINATION_DIRECTORY);

        $this->assertDirectoryEquals(__DIR__ . '/Fixture/expected', self::DESTINATION_DIRECTORY);
    }

    public function test3rdParty(): void
    {
        $rectorRecipe = $this->createConfiguration(__DIR__ . '/Source/config/some_set.php', false);
        $this->rectorGenerator->generate($rectorRecipe, self::DESTINATION_DIRECTORY);

        $this->assertDirectoryEquals(__DIR__ . '/Fixture/expected_3rd_party', self::DESTINATION_DIRECTORY);
    }

    private function createConfiguration(string $setFilePath, bool $isRectorRepository): RectorRecipe
    {
        return StaticRectorRecipeFactory::createRectorRecipe($setFilePath, $isRectorRepository);
    }
}
