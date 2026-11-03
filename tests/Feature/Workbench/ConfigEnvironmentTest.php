<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Workbench;

use Ipsocode\Cin7\Tests\TestCase;
use SimpleXMLElement;
use Symfony\Component\Yaml\Yaml;

class ConfigEnvironmentTest extends TestCase
{
    public function testTestbenchYamlAndPhpunitXmlSetTheSameEnvironment(): void
    {
        $root = dirname(__DIR__, 3);

        $yaml = array_map(
            static fn (mixed $value): string => is_bool($value) ? ($value ? 'true' : 'false') : (string) $value,
            Yaml::parseFile($root . '/testbench.yaml')['env'],
        );

        // Only testbench.yaml sets it: PHPUnit turns "true" into 1, which the SDK rejects as a boolean.
        unset($yaml['OTEL_SDK_DISABLED']);

        $phpunit = [];

        foreach ((new SimpleXMLElement((string) file_get_contents($root . '/phpunit.xml')))->xpath('//php/env[not(@force)]') as $env) {
            $phpunit[(string) $env['name']] = (string) $env['value'];
        }

        ksort($yaml);
        ksort($phpunit);

        $this->assertSame($yaml, $phpunit);
    }
}
