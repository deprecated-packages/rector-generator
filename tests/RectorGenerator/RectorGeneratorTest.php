<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Tests\RectorGenerator;

use Nette\Utils\FileSystem;
use Rector\RectorGenerator\Generator\RectorGenerator;
use Rector\RectorGenerator\Tests\RectorGenerator\Source\StaticRectorRecipeFactory;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

final class RectorGeneratorTest extends AbstractLazyTestCase
{
    /**
     * @var string
     */
    private const DESTINATION_DIRECTORY = __DIR__ . '/__temp';

    private RectorGenerator $rectorGenerator;

    protected function setUp(): void
    {
        $container = self::getContainer();
        $container->import(__DIR__ . '/../../config/config.php');

        $this->rectorGenerator = $this->make(RectorGenerator::class);
    }

    protected function tearDown(): void
    {
        // cleanup temporary data
        FileSystem::delete(self::DESTINATION_DIRECTORY);
    }

    public function test(): void
    {
        $rectorRecipe = StaticRectorRecipeFactory::createRectorRecipe();
        $this->rectorGenerator->generate($rectorRecipe, self::DESTINATION_DIRECTORY);

        $fileInfos = $this->findFileInfos(__DIR__ . '/Fixture/expected/');

        foreach ($fileInfos as $fileInfo) {
            $this->assertFileExists(self::DESTINATION_DIRECTORY . '/' . $fileInfo->getRelativePathname());

            $this->assertFileEquals(
                $fileInfo->getRealPath(),
                self::DESTINATION_DIRECTORY . '/' . $fileInfo->getRelativePathname()
            );
        }
    }

    /**
     * @return SplFileInfo[]
     */
    private function findFileInfos(string $directory): array
    {
        $fileInfos = Finder::create()
            ->in($directory)
            ->files()
            ->sortByName()
            ->getIterator();

        return iterator_to_array($fileInfos);
    }
}
