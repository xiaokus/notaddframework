<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime      2016-10-21 09:50
 */
namespace Notadd\Foundation\Http;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Debug\ExceptionHandler as ExceptionHandlerContract;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Router;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Notadd\Foundation\JWTAuth\Exceptions\TokenInvalidException;
use Notadd\Foundation\Permission\Exceptions\PermissionException;
use Notadd\Foundation\Routing\Traits\Helpers;
use Predis\Connection\ConnectionException;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Whoops\Handler\PrettyPageHandler;
use Whoops\Run as Whoops;

/**
 * Class Handler.
 */
class ExceptionHandler implements ExceptionHandlerContract
{
    use Helpers;

    /**
     * @var array
     */
    protected $dontReport = [];

    /**
     * Report or log an exception. This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $exception
     *
     * @throws \Exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if ($this->shouldntReport($exception)) {
            return;
        }
        try {
            $logger = $this->container->make(LoggerInterface::class);
        } catch (Exception $ex) {
            throw $exception;
        }
        $logger->error($exception);
    }

    /**
     * Determine if the exception should be reported.
     *
     * @param \Exception $exception
     *
     * @return bool
     */
    public function shouldReport(Exception $exception)
    {
        return !$this->shouldntReport($exception);
    }

    /**
     * Determine if the exception is in the "do not report" list.
     *
     * @param \Exception $exception
     *
     * @return bool
     */
    protected function shouldntReport(Exception $exception)
    {
        $dontReport = array_merge($this->dontReport, [HttpResponseException::class]);
        foreach ($dontReport as $type) {
            if ($exception instanceof $type) {
                return true;
            }
        }

        return false;
    }

    /**
     * Prepare exception for rendering.
     *
     * @param \Exception $exception
     *
     * @return \Exception
     */
    protected function prepareException(Exception $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            $exception = new NotFoundHttpException($exception->getMessage(), $exception);
        } else {
            if ($exception instanceof AuthorizationException) {
                $exception = new HttpException(403, $exception->getMessage());
            }
        }

        return $exception;
    }

    /**
     * Render an exception into a response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception)
    {
        if (method_exists($exception, 'render') && $response = $exception->render($request)) {
            return Router::toResponse($request, $response);
        } else if ($exception instanceof Responsable) {
            return $exception->toResponse($request);
        }
        $exception = $this->prepareException($exception);
        if ($exception instanceof HttpResponseException) {
            return $exception->getResponse();
        } else if ($exception instanceof TokenInvalidException) {
            if ($request->expectsJson()) {
                return $this->response->json(['error' => 'Unauthenticated.'], 401);
            }

            return $this->redirector->guest('login');
        } else if ($exception instanceof AuthenticationException) {
            return $this->unauthenticated($request, $exception);
        } else if ($exception instanceof PermissionException) {
            return $this->permissionDenied($request, $exception);
        } else if ($exception instanceof ValidationException) {
            return $this->convertValidationExceptionToResponse($exception, $request);
        } else if ($exception instanceof ConnectionException) {
            if ($request->expectsJson()) {
                $message = 'Redis 服务异常或未开启！';
                $data = $this->config->get('app.debug') ? [
                    'message'   => $message,
                    'exception' => get_class($exception),
                    'file'      => $exception->getFile(),
                    'line'      => $exception->getLine(),
                    'trace'     => collect($exception->getTrace())->map(function ($trace) {
                        return Arr::except($trace, ['args']);
                    })->all(),
                ] : [
                    'message' => $message,
                ];

                return new JsonResponse(
                    $data,
                    500,
                    [],
                    JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
                );
            } else {
                return $this->prepareResponse($request, new ConnectionException($exception->getConnection(), '运行 Notadd 前，请确保 Redis 服务可用！'));
            }
        }

        return $request->expectsJson()
            ? $this->prepareJsonResponse($request, $exception)
            : $this->prepareResponse($request, $exception);
    }

    /**
     * Prepare response containing exception render.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function prepareResponse($request, Exception $exception)
    {
        if (!$this->isHttpException($exception) && config('app.debug')) {
            return $this->toIlluminateResponse(
                $this->convertExceptionToResponse($exception), $exception
            );
        }
        if (!$this->isHttpException($exception)) {
            $exception = new HttpException(500, $exception->getMessage());
        }

        return $this->toIlluminateResponse(
            $this->renderHttpException($exception), $exception
        );
    }

    /**
     * Prepare a JSON response for the given exception.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $exception
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function prepareJsonResponse($request, Exception $exception)
    {
        $status = $this->isHttpException($exception) ? $exception->getStatusCode() : 500;
        $headers = $this->isHttpException($exception) ? $exception->getHeaders() : [];

        return new JsonResponse(
            $this->convertExceptionToArray($exception), $status, $headers,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }

    /**
     * Convert the given exception to an array.
     *
     * @param  \Exception $exception
     *
     * @return array
     */
    protected function convertExceptionToArray(Exception $exception)
    {
        $errors = [
            'message'   => $exception->getMessage(),
            'exception' => get_class($exception),
            'file'      => $exception->getFile(),
            'line'      => $exception->getLine(),
            'trace'     => collect($exception->getTrace())->map(function ($trace) {
                return Arr::except($trace, ['args']);
            })->all(),
        ];
        if ($this->config->get('app.debug')) {
            return $errors;
        } else {
            return [
                'message' => $this->isHttpException($exception) ? $exception->getMessage() : 'Server Error',
            ];
        }
    }

    /**
     * Map exception into an illuminate response.
     *
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param \Exception                                 $exception
     *
     * @return \Illuminate\Http\Response
     */
    protected function toIlluminateResponse($response, Exception $exception)
    {
        if ($response instanceof SymfonyRedirectResponse) {
            $response = new RedirectResponse($response->getTargetUrl(), $response->getStatusCode(),
                $response->headers->all());
        } else {
            $response = new Response($response->getContent(), $response->getStatusCode(), $response->headers->all());
        }

        return $response->withException($exception);
    }

    /**
     * Render an exception to the console.
     *
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     * @param \Exception                                        $exception
     *
     * @return void
     */
    public function renderForConsole($output, Exception $exception)
    {
        (new ConsoleApplication())->renderException($exception, $output);
    }

    /**
     * Render the given HttpException.
     *
     * @param \Symfony\Component\HttpKernel\Exception\HttpException $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function renderHttpException(HttpException $exception)
    {
        $status = $exception->getStatusCode();
        if ($this->view->exists("error::{$status}") && !$this->config->get('app.debug')) {
            return $this->response->view("error::{$status}", ['exception' => $exception], $status,
                $exception->getHeaders());
        } else {
            return $this->convertExceptionToResponse($exception);
        }
    }

    /**
     * Create a response object from the given validation exception.
     *
     * @param \Illuminate\Validation\ValidationException $exception
     * @param \Illuminate\Http\Request                   $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertValidationExceptionToResponse(ValidationException $exception, $request)
    {
        if ($exception->response) {
            return $exception->response;
        }
        $errors = $exception->validator->errors()->getMessages();
        if ($request->expectsJson()) {
            return $this->response->json($errors, 422);
        }

        return $this->redirector->back()->withInput($request->input())->withErrors($errors);
    }

    /**
     * Create a Symfony response for the given exception.
     *
     * @param \Exception $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function convertExceptionToResponse(Exception $exception)
    {
        $headers = $this->isHttpException($exception) ? $exception->getHeaders() : [];
        $statusCode = $this->isHttpException($exception) ? $exception->getStatusCode() : 500;
        try {
            $content = config('app.debug') && class_exists(Whoops::class)
                ? $this->renderExceptionWithWhoops($exception)
                : $this->renderExceptionWithSymfony($exception, config('app.debug'));
        } catch (Exception $e) {
            $content = $content ?? $this->renderExceptionWithSymfony($e, config('app.debug'));
        }

        return SymfonyResponse::create($content, $statusCode, $headers);
    }

    /**
     * Render an exception to a string using Symfony.
     *
     * @param  \Exception $exception
     * @param  bool       $debug
     *
     * @return string
     */
    protected function renderExceptionWithSymfony(Exception $exception, $debug)
    {
        return (new SymfonyExceptionHandler($debug))->getHtml(
            FlattenException::create($exception)
        );
    }

    /**
     * Render an exception to a string using "Whoops".
     *
     * @param \Exception $exception
     *
     * @return string
     */
    protected function renderExceptionWithWhoops(Exception $exception)
    {
        return tap(new Whoops, function (Whoops $whoops) {
            $whoops->pushHandler($this->whoopsHandler());
            $whoops->writeToOutput(false);
            $whoops->allowQuit(false);
        }
        )->handleException($exception);
    }

    /**
     * Get the Whoops handler for the application.
     *
     * @return \Whoops\Handler\Handler
     */
    protected function whoopsHandler()
    {
        return tap(new PrettyPageHandler, function (PrettyPageHandler $handler) {
            $files = new Filesystem;
            $handler->handleUnconditionally(true);
            $handler->setApplicationPaths(
                array_flip(Arr::except(
                    array_flip($files->directories(base_path())), [base_path('vendor')]
                ))
            );
        });
    }

    /**
     * Determine if the given exception is an HTTP exception.
     *
     * @param \Exception $exception
     *
     * @return bool
     */
    protected function isHttpException(Exception $exception)
    {
        return $exception instanceof HttpException;
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param \Illuminate\Http\Request                 $request
     * @param \Illuminate\Auth\AuthenticationException $exception
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return $this->response->json(['error' => 'Unauthenticated.'], 401);
        }

        return $this->redirector->guest('login');
    }

    /**
     * Convert an permission exception into an permission denied response.
     *
     * @param \Illuminate\Http\Request                                     $request
     * @param \Notadd\Foundation\Permission\Exceptions\PermissionException $exception
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    protected function permissionDenied($request, PermissionException $exception)
    {
        if ($request->expectsJson()) {
            return $this->response->json([
                'code'    => 406,
                'message' => 'Permission Denied.',
            ], 406);
        }

        return $this->redirector->guest('login');
    }
}
