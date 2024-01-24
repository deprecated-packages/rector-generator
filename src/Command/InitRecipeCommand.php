<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

final class InitRecipeCommand extends Command
{
    /**
     * @var string
     */
    private const RECIPE_FILE_NAME = 'rector-recipe.php';

    protected function configure(): void
    {
        $this->setName('init-recipe');
        $this->setDescription('[DEV] Initialize "rector-recipe.php" config');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);

        $templateFilePath = __DIR__ . '/../../templates/rector-recipe.php';
        $targetFilePath = getcwd() . '/' . self::RECIPE_FILE_NAME;

        if (file_exists($targetFilePath)) {
            $symfonyStyle->warning(sprintf('Config file "%s" already exists', self::RECIPE_FILE_NAME));
            return self::SUCCESS;
        }

        $filesystem = new Filesystem();
        $filesystem->copy($templateFilePath, $targetFilePath);

        $symfonyStyle->success(sprintf('"%s" config file was added', self::RECIPE_FILE_NAME));

        return self::SUCCESS;
    }
}
