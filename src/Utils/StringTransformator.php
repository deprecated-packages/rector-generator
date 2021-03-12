<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Utils;

use Nette\Utils\Strings;

/**
 * @todo use https://github.com/danielstjules/Stringy instead
 * @todo possibly decouple to symplify/package-builder
 */
final class StringTransformator
{
    /**
     * @var string
     * @see https://regex101.com/r/4w2of2/2
     */
    private const CAMEL_CASE_SPLIT_REGEX = '#([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)#';

    public function dashesToCamelCase(string $input): string
    {
        $parts = explode('-', $input);
        $uppercasedParts = array_map('ucfirst', $parts);
        return implode('', $uppercasedParts);
    }

    public function camelCaseToDashes(string $input): string
    {
        return $this->camelCaseToGlue($input, '-');
    }

    public function underscoreToCamelCase(string $input): string
    {
        $input = $this->underscoreToPascalCase($input);
        return lcfirst($input);
    }

    public function underscoreToPascalCase(string $input): string
    {
        $parts = explode('_', $input);
        $uppercasedParts = array_map('ucfirst', $parts);
        return implode('', $uppercasedParts);
    }

    public function uppercaseUnderscoreToCamelCase(string $input): string
    {
        $input = strtolower($input);
        return $this->underscoreToCamelCase($input);
    }

    private function camelCaseToGlue(string $input, string $glue): string
    {
        if ($input === strtolower($input)) {
            return $input;
        }

        $matches = Strings::matchAll($input, self::CAMEL_CASE_SPLIT_REGEX);
        $parts = [];
        foreach ($matches as $match) {
            $parts[] = $match[0] === strtoupper($match[0]) ? strtolower($match[0]) : lcfirst($match[0]);
        }

        return implode($glue, $parts);
    }
}
