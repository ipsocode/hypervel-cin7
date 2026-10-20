<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Sync;

use Ipsocode\Cin7\Sync\Module;
use Ipsocode\Cin7\Tests\TestCase;

/**
 * The sync modules, in the order of `Module::cases()`, as `config/cin7.php` and `docs/sync.md`
 * list them by hand.
 *
 * @see docs/sync.md
 */
class ModuleDocsTest extends TestCase
{
    public function testTheConfigCommentListsTheModulesInOrder(): void
    {
        $config = $this->read('config/cin7.php');

        $this->assertSame(1, preg_match("/'\\*' is every module in that order:(.*?)'modules' =>/s", $config, $match), 'config/cin7.php has no "\'*\' is every module" comment.');

        $text = trim((string) preg_replace('~^\s*//~m', '', $match[1]));

        // The reference books in parentheses, then the rest of the sentence.
        $this->assertSame(1, preg_match('/\((.*?)\),\s*then(.*)\.$/s', $text, $parts), 'The comment in config/cin7.php does not list the modules as expected.');

        $listed = [
            ...$this->words($parts[1], '/,/'),
            ...$this->words($parts[2], '/,|\band\b/'),
        ];

        $this->assertSame($this->values(), $listed, 'config/cin7.php lists the modules in a different order than Module::cases().');
    }

    public function testTheDocsTableListsTheModulesInOrderWithTheirIdentifiers(): void
    {
        $listed = [];
        $identifiers = [];

        foreach (explode("\n", $this->read('docs/sync.md')) as $line) {
            if (preg_match('/^\|\s*\d+\s*\|(.+?)\|\s*`([^`]+)`\s*\|/', $line, $row) !== 1) {
                continue;
            }

            preg_match_all('/`([^`]+)`/', $row[1], $modules);

            foreach ($modules[1] as $module) {
                $listed[] = $module;
                $identifiers[$module] = $row[2];
            }
        }

        $this->assertSame($this->values(), $listed, 'docs/sync.md lists the modules in a different order than Module::cases().');

        foreach (Module::cases() as $module) {
            $this->assertSame($module->idKey(), $identifiers[$module->value], "docs/sync.md names a different identifier for {$module->value} than Module::idKey().");
        }
    }

    /**
     * @return list<string>
     */
    private function values(): array
    {
        return array_column(Module::cases(), 'value');
    }

    /**
     * @return list<string>
     */
    private function words(string $text, string $separator): array
    {
        $words = preg_split($separator, (string) preg_replace('/\s+/', ' ', $text));

        return array_values(array_filter(array_map('trim', $words === false ? [] : $words), static fn (string $word): bool => $word !== ''));
    }

    private function read(string $path): string
    {
        return (string) file_get_contents(dirname(__DIR__, 3) . '/' . $path);
    }
}
