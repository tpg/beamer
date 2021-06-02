<?php

declare(strict_types=1);

namespace TPG\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use TPG\Beamer\BeamerServiceProvider;
use TPG\Beamer\Facades\Beamer;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->setupStorage();
    }

    protected function setupStorage(): void
    {
        config([
            'filesystems.disks.beamer' => [
                'driver' => 'local',
                'root' => __DIR__,
            ],
            'beamer.disk' => 'beamer',
        ]);
    }

    protected function getPackageProviders($app): array
    {
        return [
            BeamerServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Beamer' => Beamer::class,
        ];
    }
}
