<?php

declare(strict_types=1);

namespace TPG\Beamer\Exceptions;

use Exception;

class NotReadableException extends Exception
{
    public static function withFilename(string $filename): NotReadableException
    {
        return new self('Unable to open stream to '.$filename);
    }
}
