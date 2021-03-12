<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Config;

use Nette\Utils\Strings;
use PhpParser\Parser;
use PhpParser\PrettyPrinter\Standard;
use Rector\RectorGenerator\Exception\ShouldNotHappenException;
use Rector\RectorGenerator\TemplateFactory;
use Rector\RectorGenerator\ValueObject\RectorRecipe;
use Symplify\SmartFileSystem\SmartFileSystem;

final class ConfigFilesystem
{
    /**
     * @var string
     */
    public const RECTOR_FQN_NAME_PATTERN = 'Rector\__Package__\Rector\__Category__\__Name__';

    /**
     * @var string[]
     */
    private const REQUIRED_KEYS = ['__Package__', '__Category__', '__Name__'];

    /**
     * @see https://regex101.com/r/gJ0bHJ/1
     */
    private const LAST_ITEM_REGEX = '#;\n};#';

    /**
     * @var TemplateFactory
     */
    private $templateFactory;

    /**
     * @var Parser
     */
    private $parser;

    /**
     * @var SmartFileSystem
     */
    private $smartFileSystem;

    /**
     * @var Standard
     */
    private $standard;

    public function __construct(
        Standard $standard,
        Parser $parser,
        SmartFileSystem $smartFileSystem,
        TemplateFactory $templateFactory
    ) {
        $this->templateFactory = $templateFactory;
        $this->parser = $parser;
        $this->standard = $standard;
        $this->smartFileSystem = $smartFileSystem;
    }

    /**
     * @param array<string, string> $templateVariables
     */
    public function appendRectorServiceToSet(RectorRecipe $rectorRecipe, array $templateVariables): void
    {
        // no set required, skip it
        if ($rectorRecipe->getSetFilePath() === null) {
            return;
        }

        $setFilePath = $rectorRecipe->getSetFilePath();
        $setFileContents = $this->smartFileSystem->readFile($setFilePath);

        $this->ensureRequiredKeysAreSet($templateVariables);

        // already added?
        $servicesFullyQualifiedName = $this->templateFactory->create(self::RECTOR_FQN_NAME_PATTERN, $templateVariables);
        if (Strings::contains($setFileContents, $servicesFullyQualifiedName)) {
            return;
        }

        $registerServiceLine = sprintf(';' . PHP_EOL . '    $services->set(\%s::class);' . PHP_EOL . '};', $servicesFullyQualifiedName);
        $setFileContents = Strings::replace($setFileContents, self::LAST_ITEM_REGEX, $registerServiceLine);

        // 3. print the content back to file
        $this->smartFileSystem->dumpFile($setFilePath, $setFileContents);
    }

    /**
     * @param array<string, mixed> $templateVariables
     */
    private function ensureRequiredKeysAreSet(array $templateVariables): void
    {
        $missingKeys = array_diff(self::REQUIRED_KEYS, array_keys($templateVariables));
        if ($missingKeys === []) {
            return;
        }

        $message = sprintf('Template variables for "%s" keys are missing', implode('", "', $missingKeys));
        throw new ShouldNotHappenException($message);
    }
}
