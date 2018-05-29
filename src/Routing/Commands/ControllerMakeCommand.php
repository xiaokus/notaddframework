<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-22 14:48
 */
namespace Notadd\Foundation\Routing\Commands;

use Carbon\Carbon;
use Illuminate\Routing\Console\ControllerMakeCommand as IlluminateControllerMakeCommand;

/**
 * Class ControllerMakeCommand.
 */
class ControllerMakeCommand extends IlluminateControllerMakeCommand
{
    /**
     * Get stub file.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('resource')) {
            return __DIR__ . '/../../../stubs/routes/controller.stub';
        }

        return __DIR__ . '/../../../stubs/routes/controller.plain.stub';
    }

    /**
     * Replace the class name for the given stub.
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
