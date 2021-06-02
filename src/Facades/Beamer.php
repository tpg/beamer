<?php

declare(strict_types=1);

namespace TPG\Beamer\Facades;

use Illuminate\Support\Facades\Facade;
use TPG\Beamer\Streamer;

/**
 * @method static Streamer make(string $filename)
 */
class Beamer extends Facade
{
    /*
     *
     * $beamer = Beamer::make($filename)->start();
     *
     */


    protected static function getFacadeAccessor(): string
    {
        return 'beamer.facade';
    }
}
