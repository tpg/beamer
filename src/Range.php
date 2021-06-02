<?php

declare(strict_types=1);

namespace TPG\Beamer;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use JetBrains\PhpStorm\Pure;
use TPG\Beamer\Contracts\RangeInterface;

class Range implements RangeInterface
{
    protected Request $request;
    protected ?array $rangeRequest = [];
    protected int $filesize;
    protected int $start = 0;
    protected int $end = 0;

    public function __construct(Request $request, int $filesize)
    {
        $this->request = $request;
        $this->filesize = $filesize;
        $this->loadRange();
    }

    protected function loadRange(): void
    {
        preg_match('/bytes=(?<start>\d+)\-(?<end>\d+)?/m', $this->request->server('HTTP_RANGE', ''), $matches);

        $this->start = (int)Arr::get($matches, 'start', 0);
        $this->end = (int)Arr::get($matches, 'end', $this->filesize -1);
    }

    public function isRangeRequest(): bool
    {
        return (bool)$this->request->server('HTTP_RANGE');
    }

    #[Pure]
    public function size(): int
    {
        return $this->filesize;
    }

    #[Pure]
    public function start(): int
    {
        return $this->start;
    }

    #[Pure]
    public function end(): int
    {
        return $this->end;
    }

    #[Pure]
    public function length(): int
    {
        return $this->isRangeRequest()
            ? $this->end() - $this->start()
            : $this->size();
    }

    public function toArray(): array
    {
        return [
            'start' => $this->start,
            'end' => $this->end,
            'length' => $this->length(),
            'size' => $this->filesize,
        ];
    }
}
