<?php

declare(strict_types=1);

namespace TPG\Beamer;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use TPG\Beamer\Contracts\RangeInterface;
use TPG\Beamer\Exceptions\NotReadableException;

class Streamer
{
    protected mixed $stream;
    /**
     * @var \Illuminate\Contracts\Foundation\Application|mixed
     */
    protected RangeInterface $range;

    public function __construct(protected array $config, protected string $filename)
    {
        $this->openStream();
    }

    protected function openStream(): void
    {
        $this->stream = $this->storage()->readStream($this->path());
//        $this->stream = fopen($this->storage()->path($this->path()), 'rb');

        if (! $this->stream) {
            throw NotReadableException::withFilename($this->path());
        }

        $this->range = app(
            RangeInterface::class,
            ['filesize' => $this->storage()->size($this->path())]
        );
    }

    public function start()
    {
        ob_clean();

        $response = Response::make()
            ->header('Content-Type', 'video/mp4')
            ->header('Content-Length', $this->range->size());

//        header('Content-Type: video/mp4');
//        header('Content-Length: '.$this->range->size());

        if (! $this->range->isRangeRequest()) {
            $response->setContent(file_get_contents($this->storage()->path($this->path())));

            $response->send();

            return;
        }

        $this->stream($response);
    }

    protected function stream(HttpResponse $response): void
    {
        set_time_limit(0);

        $response->setStatusCode(206, 'Partial Content')
            ->header('Content-Length', $this->range->length())
            ->header('Content-Range', 'bytes '.$this->range->start().'-'.$this->range->end().'/'.$this->range->size());

//        header('HTTP/1.1 206 Partial Content');
//        header('Content-Length: '.$this->range->length());
//        header('Content-Range: bytes '.$this->range->start().'-'.$this->range->end().'/'.$this->range->size());

        fseek($this->stream, $this->range->start());

        $response->sendHeaders();

        while (! feof($this->stream) && ftell($this->stream) <= $this->range->end()) {
            $toRead = $this->config['buffer'] ?? 102400;

            $data = fread($this->stream, $toRead);

            $response->setContent($data);
            $response->sendContent();

        }
    }

    protected function storage(): Filesystem
    {
        return Storage::disk($this->config['disk']);
    }

    protected function path(): string
    {
        $path = $this->config['path'];
        if (! Str::endsWith($path, '/')) {
            $path .= '/';
        }

        return $path.$this->filename;
    }
}
