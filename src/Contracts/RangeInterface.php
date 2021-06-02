<?php

declare(strict_types=1);

namespace TPG\Beamer\Contracts;

use Illuminate\Http\Request;

interface RangeInterface
{
    public function __construct(Request $request, int $filesize);

    public function isRangeRequest(): bool;

    public function start(): int;

    public function end(): int;

    public function size(): int;

    public function length(): int;
}
