<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\FileSystem;

use Nette\Utils\Strings;
use Rector\RectorGenerator\Enum\Packages;
use Rector\RectorGenerator\Finder\TemplateFinder;
use Rector\RectorGenerator\TemplateFactory;
use Rector\RectorGenerator\ValueObject\RectorRecipe;
use Symfony\Component\Filesystem\Filesystem;

final class TemplateFileSystem
{
    /**
     * @var string
     * @see https://regex101.com/r/fw3jBe/1
     */
    private const FIXTURE_SHORT_REGEX = '#/Fixture/#';

    public function __construct(
        private readonly TemplateFactory $templateFactory,
        private readonly Filesystem $filesystem,
    ) {
    }

    /**
     * @param array<string, string> $templateVariables
     */
    public function resolveDestination(
        string $filePath,
        array $templateVariables,
        RectorRecipe $rectorRecipe,
        string $targetDirectory
    ): string {
        $destination = $this->resolveRelativeFilepath($filePath);
        $destination = $this->changeRootPathForRootPackage($rectorRecipe, $destination);

        $destination = $this->templateFactory->create($destination, $templateVariables);

        // remove ".inc" protection from PHPUnit if not a test case
        if ($this->isNonFixtureFileWithIncSuffix($destination)) {
            $destination = Strings::before($destination, '.inc');
        }

        // special hack for tests, so PHPUnit doesn't load the generated file as a test case
        /** @var string $destination */
        if (\str_ends_with($destination, 'Test.php') && defined('PHPUNIT_COMPOSER_INSTALL')) {
            $destination .= '.inc';
        }

        return $targetDirectory . DIRECTORY_SEPARATOR . $destination;
    }

    private function isNonFixtureFileWithIncSuffix(string $filePath): bool
    {
        if (Strings::match($filePath, self::FIXTURE_SHORT_REGEX) !== null) {
            return false;
        }

        return \str_ends_with($filePath, '.inc');
    }

    private function changeRootPathForRootPackage(RectorRecipe $rectorRecipe, string $destination): string
    {
        // rector split package? path are in the root directory
        if (! in_array($rectorRecipe->getPackage(), Packages::RECTOR_CORE, true)) {
            return $destination;
        }

        $destination = str_replace('rules/__Package__', 'src', $destination);
        return str_replace('rules-tests/__Package__', 'tests', $destination);
    }

    private function resolveRelativeFilepath(string $filePath): string
    {
        $destination = $this->filesystem->makePathRelative($filePath, TemplateFinder::TEMPLATES_DIRECTORY);

        return rtrim($destination, '/');
    }
}
