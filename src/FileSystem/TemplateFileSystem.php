<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\FileSystem;

use Nette\Utils\Strings;
use Rector\RectorGenerator\Finder\TemplateFinder;
use Rector\RectorGenerator\TemplateFactory;
use Rector\RectorGenerator\ValueObject\RectorRecipe;
use Symplify\SmartFileSystem\SmartFileInfo;

final class TemplateFileSystem
{
    /**
     * @var string[]
     */
    public const ROOT_PACKAGES = ['Symfony', 'Nette', 'Doctrine', 'Laravel', 'PHPUnit', 'CakePHP'];

    /**
     * @var string
     * @see https://regex101.com/r/fw3jBe/1
     */
    private const FIXTURE_SHORT_REGEX = '#/Fixture/#';

    /**
     * @var string
     * @see https://regex101.com/r/HBcfXd/1
     */
    private const PACKAGE_RULES_PATH_REGEX = '#(rules)\/__Package__#i';

    /**
     * @var string
     * @see https://regex101.com/r/HBcfXd/1
     */
    private const PACKAGE_RULES_TESTS_PATH_REGEX = '#(rules-tests)\/__Package__#i';

    /**
     * @var string
     * @see https://regex101.com/r/tOidWU/1
     */
    private const CONFIGURED_OR_EXTRA_REGEX = '#(__Configured|__Extra)#';

    public function __construct(
        private readonly TemplateFactory $templateFactory
    ) {
    }

    /**
     * @param array<string, string> $templateVariables
     */
    public function resolveDestination(
        SmartFileInfo $smartFileInfo,
        array $templateVariables,
        RectorRecipe $rectorRecipe,
        string $targetDirectory
    ): string {
        $destination = $smartFileInfo->getRelativeFilePathFromDirectory(TemplateFinder::TEMPLATES_DIRECTORY);
        $destination = $this->changeRootPathForRootPackage($rectorRecipe, $destination);

        // normalize core package
        if (! $rectorRecipe->isRectorRepository()) {
            // special keyword for 3rd party Rectors, not for core Github contribution
            $destination = Strings::replace($destination, self::PACKAGE_RULES_PATH_REGEX, 'utils/rector/src');
            $destination = Strings::replace($destination, self::PACKAGE_RULES_TESTS_PATH_REGEX, 'utils/rector/tests');
        }

        // remove _Configured|_Extra prefix
        $destination = $this->templateFactory->create($destination, $templateVariables);

        $destination = Strings::replace($destination, self::CONFIGURED_OR_EXTRA_REGEX, '');

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
        if (! in_array($rectorRecipe->getPackage(), self::ROOT_PACKAGES, true)) {
            return $destination;
        }

        $destination = str_replace('rules/__Package__', 'src', $destination);
        return str_replace('rules-tests/__Package__', 'tests', $destination);
    }
}
