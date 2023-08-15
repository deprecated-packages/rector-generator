<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Tests\Config;

use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use Rector\RectorGenerator\FileSystem\ConfigFilesystem;
use Rector\RectorGenerator\Tests\RectorGenerator\Source\StaticRectorRecipeFactory;
use Rector\RectorGenerator\ValueObject\NamePattern;
use Rector\Testing\Fixture\FixtureSplitter;
use Rector\Testing\PHPUnit\AbstractLazyTestCase;

final class ConfigFilesystemTest extends AbstractLazyTestCase
{
    private ConfigFilesystem $configFilesystem;

    protected function setUp(): void
    {
        $this->configFilesystem = $this->make(ConfigFilesystem::class);
    }

    #[DataProvider('provideData')]
    public function test(string $filePath): void
    {
        [$inputContents, $expectedContents] = FixtureSplitter::split($filePath);

        $setTempFilePath = sys_get_temp_dir() . '/temp-set-path.php';
        \Nette\Utils\FileSystem::write($setTempFilePath, $inputContents);

        $rectorRecipe = StaticRectorRecipeFactory::createRectorRecipe($setTempFilePath, false);

        $setTempFilePath = $rectorRecipe->getSetFilePath();
        $this->assertNotNull($setTempFilePath);

        /** @var string $setTempFilePath */
        $this->configFilesystem->appendRectorServiceToSet($setTempFilePath, [
            '__Package__' => 'SomePackage',
            '__Category__' => 'String_',
            '__Name__' => 'SomeRector',
        ], NamePattern::RECTOR_FQN_NAME_PATTERN);

        $this->assertSame($expectedContents, \Nette\Utils\FileSystem::read($setTempFilePath));
    }

    public static function provideData(): Iterator
    {
        yield [__DIR__ . '/Fixture/some_set.php.inc'];
    }
}
