<?php

namespace App\Exceptions;

use App\Traits\HasJsonResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Exceptions\InvalidSignatureException;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException as LaravelValidationException;
use Symfony\Component\HttpFoundation\Exception\SuspiciousOperationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    use HasJsonResponse;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        SuspiciousOperationException::class,
    ];

    /**
     * {@inheritDoc}
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * {@inheritDoc}
     */
    public function render($request, Throwable $exception)
    {
        $this->reportable(function (Throwable $e) {
            //
        });
        $exception = $this->prepareException($exception);

        if ($response = $this->responsableException($exception, $request)) {
            return $response;
        }

        if ($exception instanceof HttpException) {
            return $this->convertHttpExceptionToJson($exception);
        }

        return parent::render($request, $exception);
    }

    /**
     * {@inheritDoc}
     */
    protected function prepareException(Throwable $e)
    {
        if ($e instanceof ModelNotFoundException) {
            return new NotFoundHttpException('Resource not found');
        }
        if ($e instanceof InvalidSignatureException) {
            return new HttpException(HTTP_FORBIDDEN, 'This url is no longer valid or has expired!');
        }
        if ($e instanceof AuthenticationException) {
            return new HttpException(HTTP_UNAUTHENTICATED, $e->getMessage(), $e);
        }
        if ($e instanceof UnauthorizedException) {
            return new HttpException(HTTP_FORBIDDEN, $e->getMessage(), $e);
        }
        if ($e instanceof SuspiciousOperationException) {
            return new NotFoundHttpException('Resource not found', $e);
        }

        return parent::prepareException($e);
    }

    protected function responsableException(Throwable $exception, $request): ?JsonResponse
    {
        if ($exception instanceof HttpResponseException) {
            return $this->wrapJsonResponse($exception->getResponse(), 'An error occurred.');
        }

        if ($exception instanceof LaravelValidationException) {
            return (new ValidationException($exception->validator))->render($request);
        }

        return null;
    }

    protected function convertHttpExceptionToJson(HttpException $exception): JsonResponse
    {
        $statusCode = $exception->getStatusCode();
        $message = $exception->getMessage() ?: Response::$statusTexts[$statusCode];
        $headers = $exception->getHeaders();
        $data = ($exception instanceof HttpExceptionWithErrorData) ? $exception->getErrorDetails() : [];

        return $this->jsonResponse($statusCode, $message, $data, $headers);
    }
}
