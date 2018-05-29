<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-22 14:57
 */
namespace Notadd\Foundation\Routing\Commands;

use Carbon\Carbon;
use Illuminate\Routing\Console\MiddlewareMakeCommand as IlluminateMiddlewareMakeCommand;

/**
 * Class MiddlewareMakeCommand.
 */
class MiddlewareMakeCommand extends IlluminateMiddlewareMakeCommand
{
    /**
     * Get stub file.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../../../stubs/routes/middleware.stub';
    }

    /**
     * Replace class name by holder.
     *
     * @param string $stub
     * @param string $name
     *
     * @return mixed
     */
    protected function replaceClass($stub, $name)
    {
        $stub = parent::replaceClass($stub, $name);

        return str_replace('DummyDatetime', Carbon::now()->toDateTimeString(), $stub);
    }
}
