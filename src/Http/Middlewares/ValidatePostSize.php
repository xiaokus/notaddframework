<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-21 14:33
 */
namespace Notadd\Foundation\Http\Middlewares;

use Closure;
use Illuminate\Http\Exceptions\PostTooLargeException;

/**
 * Class VerifyPostSize.
 */
class ValidatePostSize
{
    /**
     * Middleware handler.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @throws \Illuminate\Http\Exceptions\PostTooLargeException
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $max = $this->getPostMaxSize();

        if ($max > 0 && $request->server('CONTENT_LENGTH') > $max) {
            throw new PostTooLargeException;
        }

        return $next($request);
    }

    /**
     * Get PHP max post size.
     * @return int
     */
    protected function getPostMaxSize()
    {
        if (is_numeric($postMaxSize = ini_get('post_max_size'))) {
            return (int)$postMaxSize;
        }
        $metric = strtoupper(substr($postMaxSize, -1));
        switch ($metric) {
            case 'K':
                return (int)$postMaxSize * 1024;
            case 'M':
                return (int)$postMaxSize * 1048576;
            case 'G':
                return (int)$postMaxSize * 1073741824;
            default:
                return (int)$postMaxSize;
        }
    }
}
