<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-21 11:03
 */
namespace Notadd\Foundation\Http\Bootstraps;

use ErrorException;
use Exception;
use Notadd\Foundation\Http\Contracts\Bootstrap;
use Notadd\Foundation\Routing\Traits\Helpers;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Debug\Exception\FatalErrorException;
use Symfony\Component\Debug\Exception\FatalThrowableError;

/**
 * Class HandleExceptions.
 */
class HandleExceptions implements Bootstrap
{
    use Helpers;

    /**
     * Bootstrap the given application.
     */
    public function bootstrap()
    {
        error_reporting(-1);
        set_error_handler([
            $this,
            'handleError',
        ]);
        set_exception_handler([
            $this,
            'handleException',
        ]);
        register_shutdown_function([
            $this,
            'handleShutdown',
        ]);
        if (!$this->container->environment('testing')) {
            ini_set('display_errors', 'Off');
        }
        if ($this->config->get('app.debug')) {
            ini_set('display_errors', true);
        }
    }

    /**
     * Convert a PHP error to an ErrorException.
     *
     * @param int    $level
     * @param string $message
     * @param string $file
     * @param int    $line
     * @param array  $context
     *
     * @throws \ErrorException
     */
    public function handleError($level, $message, $file = '', $line = 0, $context = [])
    {
        if (error_reporting() & $level) {
            throw new ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * Handle an uncaught exception from the application.
     *
     * @param \Throwable $exception
     */
    public function handleException($exception)
    {
        if (!$exception instanceof Exception) {
            $exception = new FatalThrowableError($exception);
        }
        $this->getExceptionHandler()->report($exception);
        if ($this->container->runningInConsole()) {
            $this->renderForConsole($exception);
        } else {
            $this->renderHttpResponse($exception);
        }
    }

    /**
     * Render an exception to the console.
     *
     * @param \Exception $e
     *
     * @return void
     */
    protected function renderForConsole(Exception $e)
    {
        $this->getExceptionHandler()->renderForConsole(new ConsoleOutput(), $e);
    }

    /**
     * Render an exception as an HTTP response and send it.
     *
     * @param \Exception $exception
     */
    protected function renderHttpResponse(Exception $exception)
    {
        $this->getExceptionHandler()->render($this->container['request'], $exception)->send();
    }

    /**
     * Handle the PHP shutdown event.
     */
    public function handleShutdown()
    {
        if (!is_null($error = error_get_last()) && $this->isFatal($error['type'])) {
            $this->handleException($this->fatalExceptionFromError($error, 0));
        }
    }

    /**
     * Create a new fatal exception instance from an error array.
     *
     * @param array    $error
     * @param int|null $traceOffset
     *
     * @return \Symfony\Component\Debug\Exception\FatalErrorException
     */
    protected function fatalExceptionFromError(array $error, $traceOffset = null)
    {
        return new FatalErrorException($error['message'], $error['type'], 0, $error['file'], $error['line'], $traceOffset);
    }

    /**
     * Determine if the error type is fatal.
     *
     * @param int $type
     *
     * @return bool
     */
    protected function isFatal($type)
    {
        return in_array($type, [
            E_ERROR,
            E_CORE_ERROR,
            E_COMPILE_ERROR,
            E_PARSE,
        ]);
    }

    /**
     * Get an instance of the exception handler.
     *
     * @return \Illuminate\Contracts\Debug\ExceptionHandler
     */
    protected function getExceptionHandler()
    {
        return $this->container->make('Illuminate\Contracts\Debug\ExceptionHandler');
    }
}
