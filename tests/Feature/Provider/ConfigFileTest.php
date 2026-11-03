<?php

declare(strict_types=1);

namespace Ipsocode\Cin7\Tests\Feature\Provider;

use Hypervel\Support\Env;
use Ipsocode\Cin7\Cin7Connector;
use Ipsocode\Cin7\Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class ConfigFileTest extends TestCase
{
    #[DataProvider('transportValues')]
    public function testTheTransportTimeoutsAreCastInTheConfigFile(string|false $value, ?int $expected): void
    {
        foreach (['CIN7_TIMEOUT', 'CIN7_CONNECT_TIMEOUT'] as $name) {
            $this->setEnv($name, $value);
        }

        try {
            $config = require dirname(__DIR__, 3) . '/config/cin7.php';
        } finally {
            foreach (['CIN7_TIMEOUT', 'CIN7_CONNECT_TIMEOUT'] as $name) {
                $this->setEnv($name, false);
            }
        }

        $this->assertSame($expected, $config['timeout']);
        $this->assertSame($expected, $config['connect_timeout']);
    }

    /**
     * @return iterable<string, array{false|string, null|int}>
     */
    public static function transportValues(): iterable
    {
        yield 'unset' => [false, null];
        yield 'blank' => ['', null];
        yield 'numeric' => ['45', 45];
    }

    public function testTheConfigDefaultsComeFromTheConnectorConstants(): void
    {
        $config = require dirname(__DIR__, 3) . '/config/cin7.php';

        $this->assertSame(Cin7Connector::BASE_URL, $config['base_url']);
        $this->assertSame(Cin7Connector::RATE_LIMIT_MAX, $config['rate_limit']['max']);
        $this->assertSame(Cin7Connector::RATE_LIMIT_PERIOD, $config['rate_limit']['period']);
        $this->assertSame(Cin7Connector::THROTTLE_COOLDOWN, $config['rate_limit']['cooldown']);
    }

    private function setEnv(string $name, string|false $value): void
    {
        putenv($value === false ? $name : $name . '=' . $value);

        if ($value === false) {
            unset($_ENV[$name], $_SERVER[$name]);
        } else {
            $_ENV[$name] = $_SERVER[$name] = $value;
        }

        Env::flushRepository();
    }
}
