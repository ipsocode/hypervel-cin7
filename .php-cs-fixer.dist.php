<?php

declare(strict_types=1);

/*
 * Coding standard: hypervel/components' 0.4 config. Its rules are imported verbatim as
 * .github/php-cs-fixer-rules.php; only this header, the finder and the cache file differ.
 *
 * - declare_strict_types is risky: it can turn a silent coercion into a TypeError, so
 *   run `composer test` after `composer lint:fix`.
 * - php_unit_method_casing renames snake_case test methods to camelCase.
 */

use PhpCsFixer\Config;
use PhpCsFixer\Runner\Parallel\ParallelConfig;

$maxProcesses = function_exists('swoole_cpu_num') ? swoole_cpu_num() : 4;

return (new Config)
    ->setParallelConfig(new ParallelConfig($maxProcesses))
    ->setRiskyAllowed(true)
    ->setRules(require __DIR__ . '/.github/php-cs-fixer-rules.php')
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->exclude('vendor')
            // Workbench runtime storage, regenerated on every boot.
            ->exclude('workbench/storage')
            // The tools' caches; php-cs-fixer v4 does not skip dot-directories.
            ->exclude('tests/.cache')
            ->in(__DIR__)
    )
    ->setCacheFile(__DIR__ . '/tests/.cache/php-cs-fixer.cache');
