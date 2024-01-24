<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Command;

use Rector\RectorGenerator\Exception\ShouldNotHappenException;
use Rector\RectorGenerator\FileSystem\PathHelper;
use Rector\RectorGenerator\Generator\RectorGenerator;
use Rector\RectorGenerator\Provider\RectorRecipeProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * @see \Rector\RectorGenerator\Tests\RectorGenerator\GenerateCommandInteractiveModeTest
 */
final class GenerateCommand extends Command
{
    public function __construct(
        private readonly RectorRecipeProvider $rectorRecipeProvider,
        private readonly RectorGenerator $rectorGenerator,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('generate');
        $this->setDescription('[DEV] Create a new Rector, in a proper location, with new tests');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);

        $rectorRecipe = $this->rectorRecipeProvider->provide();

        $generatedFilePaths = $this->rectorGenerator->generate($rectorRecipe, getcwd());

        $testCaseDirectoryPath = $this->resolveTestCaseDirectoryPath($generatedFilePaths);
        $this->printSuccess($rectorRecipe->getName(), $generatedFilePaths, $testCaseDirectoryPath, $symfonyStyle);

        return self::SUCCESS;
    }

    /**
     * @param string[] $generatedFilePaths
     */
    private function resolveTestCaseDirectoryPath(array $generatedFilePaths): string
    {
        foreach ($generatedFilePaths as $generatedFilePath) {
            if (! $this->isGeneratedFilePathTestCase($generatedFilePath)) {
                continue;
            }

            $relativeFilePath = PathHelper::getRelativePathFromDirectory($generatedFilePath, getcwd());
            return dirname($relativeFilePath);
        }

        throw new ShouldNotHappenException();
    }

    /**
     * @param string[] $generatedFilePaths
     */
    private function printSuccess(
        string $name,
        array $generatedFilePaths,
        string $testCaseFilePath,
        SymfonyStyle $symfonyStyle
    ): void {
        $message = sprintf('New files generated for "%s":', $name);
        $symfonyStyle->title($message);

        sort($generatedFilePaths);

        foreach ($generatedFilePaths as $generatedFilePath) {
            $relativeFilePath = PathHelper::getRelativePathFromDirectory($generatedFilePath, getcwd());
            $symfonyStyle->writeln(' * ' . $relativeFilePath);
        }

        $message = sprintf('Make tests green again:%svendor/bin/phpunit %s', PHP_EOL . PHP_EOL, $testCaseFilePath);

        $symfonyStyle->success($message);
    }

    private function isGeneratedFilePathTestCase(string $generatedFilePath): bool
    {
        if (\str_ends_with($generatedFilePath, 'Test.php')) {
            return true;
        }

        if (! \str_ends_with($generatedFilePath, 'Test.php.inc')) {
            return false;
        }

        return defined('PHPUNIT_COMPOSER_INSTALL');
    }
}
