<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Config;

use Nette\Utils\Strings;
use PhpParser\Parser;
use PhpParser\PrettyPrinter\Standard;
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
        if ($rectorRecipe->getSet() === null) {
            return;
        }

        $setFilePath = $rectorRecipe->getSet();
        $setFileContents = $this->smartFileSystem->readFile($setFilePath);

        // already added?
        $rectorFqnName = $this->templateFactory->create(self::RECTOR_FQN_NAME_PATTERN, $templateVariables);
        if (Strings::contains($setFileContents, $rectorFqnName)) {
            return;
        }

        // 1. parse the file
//        $setFileContent = $this->smartFileSystem->readFile($setFileContents);
//        $setConfigNodes = $this->parser->parse($setFileContent);

        // 2. add the set() call
//        $this->decorateNamesToFullyQualified($setConfigNodes);

        dump($setFileContents);
//        $changedSetConfigContent = $this->standard->prettyPrintFile($setConfigNodes);

        dump('add with regular expression, keep it simple :)');

        // 3. print the content back to file
        $this->smartFileSystem->dumpFile($setFilePath, $setFileContents);
    }
}
