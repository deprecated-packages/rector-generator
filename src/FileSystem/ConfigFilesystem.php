<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\FileSystem;

use Nette\Utils\Strings;
use Rector\RectorGenerator\Exception\ShouldNotHappenException;
use Rector\RectorGenerator\TemplateFactory;
use Symplify\SmartFileSystem\SmartFileSystem;

final class ConfigFilesystem
{
    /**
     * @var string[]
     */
    private const REQUIRED_KEYS = ['__Package__', '__Category__', '__Name__'];

    /**
     * @see https://regex101.com/r/gJ0bHJ/1
     */
    private const LAST_ITEM_REGEX = '#;\n};#';

    public function __construct(
        private readonly SmartFileSystem $smartFileSystem,
        private readonly TemplateFactory $templateFactory
    ) {
    }

    /**
     * @param array<string, string> $templateVariables
     */
    public function appendRectorServiceToSet(
        string $setFilePath,
        array $templateVariables,
        string $rectorFqnNamePattern
    ): void {
        $setFileContents = $this->smartFileSystem->readFile($setFilePath);

        $this->ensureRequiredKeysAreSet($templateVariables);

        // already added?
        $servicesFullyQualifiedName = $this->templateFactory->create($rectorFqnNamePattern, $templateVariables);
        if (\str_contains($setFileContents, $servicesFullyQualifiedName)) {
            return;
        }

        $registerServiceLine = sprintf(
            ';' . PHP_EOL . '    $services->set(\\%s::class);' . PHP_EOL . '};',
            $servicesFullyQualifiedName
        );
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
