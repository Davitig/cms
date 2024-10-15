<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
    ];

    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $e
     * @return void
     *
     * @throws \Throwable
     */
    public function report(Throwable $e): void
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        return parent::render($request, $e);
    }

    /**
     * {@inheritDoc}
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('/');
    }

    /**
     * {@inheritDoc}
     */
    protected function renderHttpException(HttpExceptionInterface $e)
    {
        $status = $e->getStatusCode();

        if (request()->expectsJson()) {
            if (($trans = trans('http.' . $status)) !== 'http.' . $status) {
                return response($trans, $status);
            } else {
                return response($e->getMessage(), $status);
            }
        }

        if ($view = $this->getExceptionView($status, $e)) {
            return $view;
        }

        return $this->convertExceptionToResponse($e, true);
    }

    /**
     * {@inheritDoc}
     */
    protected function convertExceptionToResponse(Throwable $e, $viewChecked = false)
    {
        $response = parent::convertExceptionToResponse($e);

        $status = $response->getStatusCode();

        $debug = config('app.debug');

        if (request()->expectsJson()) {
            if ($debug) {
                return response()->make(
                    $e->getMessage() . ' in ' . $e->getFile() . ' line ' . $e->getLine(), $status
                );
            }

            if (($trans = trans('http.' . $status)) !== 'http.' . $status) {
                return response($trans, $status);
            } else {
                return response($e->getMessage(), $status);
            }
        }

        if (! $debug && ! $viewChecked && ($view = $this->getExceptionView($status, $e))) {
            return $view;
        }

        return $response;
    }

    /**
     * Get the view for the given exception.
     *
     * @param  string  $status
     * @param  \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface  $e
     * @return \Illuminate\Http\Response|bool
     */
    protected function getExceptionView($status, HttpExceptionInterface $e)
    {
        $dir = cms_is_booted() ? 'admin' : 'web';

        if (view()->exists($dir . ".errors.{$status}")) {
            return response()->view($dir . ".errors.{$status}", ['exception' => $e], $status);
        }

        return false;
    }
}
