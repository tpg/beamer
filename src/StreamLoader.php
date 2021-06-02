<?php

declare(strict_types=1);

namespace TPG\Beamer;

use TPG\Beamer\Contracts\StreamerInterface;

class StreamLoader
{
    public function __construct(protected array $config)
    {
        //
    }

    public function make(string $filename): Streamer
    {
        return app(StreamerInterface::class, ['config' => $this->config, 'filename' => $filename]);
    }
}
