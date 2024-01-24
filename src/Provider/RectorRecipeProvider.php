<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Provider;

use PhpParser\Node\Expr\MethodCall;
use Rector\RectorGenerator\ValueObject\RectorRecipe;

final class RectorRecipeProvider
{
    private RectorRecipe $rectorRecipe;

    /**
     * Configure in the rector-recipe.php config
     * @param string[] $nodeTypes
     */
    public function __construct(
        string $package = 'Utils',
        string $name = 'SomeRector',
        array $nodeTypes = [MethodCall::class],
        string $description = 'Some description',
        string $codeBefore = 'Some code before',
        string $codeAfter = 'Some code after',
    ) {
        $this->rectorRecipe = new RectorRecipe($package, $name, $nodeTypes, $description, $codeBefore, $codeAfter);
    }

    public function provide(): RectorRecipe
    {
        return $this->rectorRecipe;
    }
}
