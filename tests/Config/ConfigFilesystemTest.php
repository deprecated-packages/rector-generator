<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Tests\Config;

use Iterator;
use Rector\RectorGenerator\FileSystem\ConfigFilesystem;
use Rector\RectorGenerator\Tests\HttpKernel\DummyKernel;
use Rector\RectorGenerator\Tests\RectorGenerator\Source\StaticRectorRecipeFactory;
use Rector\RectorGenerator\ValueObject\NamePattern;
use Symplify\EasyTesting\StaticFixtureSplitter;
use Symplify\PackageBuilder\Testing\AbstractKernelTestCase;
use Symplify\SmartFileSystem\SmartFileInfo;

final class ConfigFilesystemTest extends AbstractKernelTestCase
{
    private ConfigFilesystem $configFilesystem;

    protected function setUp(): void
    {
        $this->bootKernel(DummyKernel::class);
        $this->configFilesystem = $this->getService(ConfigFilesystem::class);
    }

    /**
     * @dataProvider provideData()
     */
    public function test(SmartFileInfo $fixtureFileInfo): void
    {
        $inputFileInfoAndExpected = StaticFixtureSplitter::splitFileInfoToLocalInputAndExpected($fixtureFileInfo);
        $inputFileInfo = $inputFileInfoAndExpected->getInputFileInfo();

        $rectorRecipe = StaticRectorRecipeFactory::createRectorRecipe($inputFileInfo->getRealPath(), false);

        $setFilePath = $rectorRecipe->getSetFilePath();
        $this->assertNotNull($setFilePath);

        /** @var string $setFilePath */
        $this->configFilesystem->appendRectorServiceToSet($setFilePath, [
            '__Package__' => 'SomePackage',
            '__Category__' => 'String_',
            '__Name__' => 'SomeRector',
        ], NamePattern::RECTOR_FQN_NAME_PATTERN);

        $this->assertSame($inputFileInfoAndExpected->getExpected(), $inputFileInfo->getContents());
    }

    /**
     * @return Iterator<SmartFileInfo[]>
     */
    public function provideData(): Iterator
    {
        yield [new SmartFileInfo(__DIR__ . '/Fixture/some_set.php.inc')];
    }
}
