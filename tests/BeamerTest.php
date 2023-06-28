<?php

declare(strict_types=1);

namespace TPG\Tests;

use TPG\Beamer\Facades\Beamer;
use TPG\Beamer\Streamer;

class BeamerTest extends TestCase
{
    /**
     * @test
     **/
    public function it_can_load_a_file_from_a_filesystem_disk(): void
    {
        $stream = Beamer::make('test.mp4');

        self::assertInstanceOf(Streamer::class, $stream);
    }
}
