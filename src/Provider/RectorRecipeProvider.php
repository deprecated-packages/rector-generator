<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Provider;

use Rector\RectorGenerator\ValueObject\RectorRecipe;

final class RectorRecipeProvider
{
    private RectorRecipe $rectorRecipe;

    /**
     * Configure in the rector-recipe.php config
     * @param string[] $nodeTypes
     */
    public function __construct(
        string $package,
        string $name,
        array $nodeTypes,
        string $description,
        string $codeBefore,
        string $codeAfter,
    ) {
        $this->rectorRecipe = new RectorRecipe($package, $name, $nodeTypes, $description, $codeBefore, $codeAfter);
    }

    public function provide(): RectorRecipe
    {
        return $this->rectorRecipe;
    }
}
