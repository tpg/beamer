<?php

declare(strict_types=1);

namespace TPG\Beamer;

use Illuminate\Support\ServiceProvider;
use TPG\Beamer\Contracts\RangeInterface;
use TPG\Beamer\Contracts\StreamerInterface;

class BeamerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/beamer.php', 'beamer'
        );

        $this->app->bind('beamer.facade', function () {
            return new StreamLoader(config('beamer'));
        });

        $this->app->bind(StreamerInterface::class, Streamer::class);
        $this->app->bind(RangeInterface::class, Range::class);
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/beamer.php' => config_path('beamer.php'),
        ]);
    }
}
