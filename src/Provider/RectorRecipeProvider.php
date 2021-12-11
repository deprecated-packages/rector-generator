<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Provider;

use Rector\RectorGenerator\ValueObject\Option;
use Rector\RectorGenerator\ValueObject\RectorRecipe;

final class RectorRecipeProvider
{
    private RectorRecipe $rectorRecipe;

    /**
     * Configure in the rector-recipe.php config
     *
     * @param array<Option::*, mixed> $configuration
     */
    public function __construct(array $configuration)
    {
        $rectorRecipe = new RectorRecipe(
            $configuration[Option::PACKAGE],
            $configuration[Option::NAME],
            $configuration[Option::NODE_TYPES],
            $configuration[Option::DESCRIPTION],
            $configuration[Option::CODE_BEFORE],
            $configuration[Option::CODE_AFTER],
        );

        // optional parameters
        if (isset($configuration[Option::CONFIGURATION])) {
            $rectorRecipe->setConfiguration($configuration[Option::CONFIGURATION]);
        }

        if (isset($configuration[Option::RESOURCES])) {
            $rectorRecipe->setResources($configuration[Option::RESOURCES]);
        }

        if (isset($configuration[Option::SET_FILE_PATH])) {
            $rectorRecipe->setResources($configuration[Option::SET_FILE_PATH]);
        }

        $this->rectorRecipe = $rectorRecipe;
    }

    public function provide(): RectorRecipe
    {
        return $this->rectorRecipe;
    }
}
