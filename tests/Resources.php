<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests;

use Hypervel\Saloon\Http\BaseResource;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use SplFileInfo;

/**
 * The resource classes under `src/Resources/`, found from the files.
 */
final class Resources
{
    /**
     * Every concrete class under `src/Resources/`, abstract bases such as `ListResource` left out.
     *
     * @return list<class-string<BaseResource>>
     */
    public static function classes(): array
    {
        $root = __DIR__ . '/../src/Resources';
        $classes = [];

        /** @var SplFileInfo $file */
        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root)) as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $class = 'Ipsocode\Cin7\Resources\\' . str_replace(['/', '.php'], ['\\', ''], substr($file->getPathname(), strlen($root) + 1));

            if (! new ReflectionClass($class)->isAbstract()) {
                $classes[] = $class;
            }
        }

        sort($classes);

        return $classes;
    }
}
