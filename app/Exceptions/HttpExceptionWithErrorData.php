<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class HttpExceptionWithErrorData extends HttpException
{
    /**
     * The error array.
     *
     * @var array
     */
    protected $error = [];

    public function __construct(int $code, array $error, string $message = null, array $headers = [], Throwable $previous = null)
    {
        $this->error = $error;

        parent::__construct($code, $message, $previous, $headers);
    }

    public function getErrorDetails(): array
    {
        return $this->error;
    }
}
