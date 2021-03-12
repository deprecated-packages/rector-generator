<?php

declare(strict_types=1);

namespace Rector\RectorGenerator\Provider;

use PhpParser\Node\Stmt;
use Rector\RectorGenerator\Exception\ShouldNotHappenException;
use ReflectionClass;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @see \Rector\RectorGenerator\Tests\Provider\NodeTypesProviderTest
 */
final class NodeTypesProvider
{
    /**
     * @var string
     */
    private const PHP_PARSER_NAMESPACE = 'PhpParser\Node\\';

    /**
     * @return array<string, string>
     */
    public function provide(): array
    {
        $finder = new Finder();
        $finder = $finder->files()
            ->in($this->resolvePhpParserNodesDirectory());

        $fileInfos = iterator_to_array($finder->getIterator());

        $nodeTypes = [];
        foreach ($fileInfos as $fileInfo) {
            /** @var SplFileInfo $fileInfo */
            $name = str_replace(['.php', '/'], ['', '\\'], $fileInfo->getRelativePathname());

            $reflectionClass = new ReflectionClass(self::PHP_PARSER_NAMESPACE . $name);
            if ($reflectionClass->isAbstract()) {
                continue;
            }

            if ($reflectionClass->isInterface()) {
                continue;
            }

            $nodeTypes[$name] = $name;
        }

        return $nodeTypes;
    }

    private function resolvePhpParserNodesDirectory(): string
    {
        $stmtReflectionClass = new ReflectionClass(Stmt::class);
        $stmtFileName = $stmtReflectionClass->getFileName();
        if ($stmtFileName === false) {
            throw new ShouldNotHappenException();
        }

        return dirname($stmtFileName);
    }
}
