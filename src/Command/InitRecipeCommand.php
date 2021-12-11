<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Command;

use Rector\RectorGenerator\TemplateInitializer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class InitRecipeCommand extends Command
{
    public function __construct(
        private readonly TemplateInitializer $templateInitializer
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('init-recipe');
        $this->setDescription('[DEV] Initialize "rector-recipe.php" config');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->templateInitializer->initialize(__DIR__ . '/../../templates/rector-recipe.php', 'rector-recipe.php');

        return self::SUCCESS;
    }
}
