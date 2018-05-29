<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-09-21 14:26
 */
namespace Notadd\Foundation\Http\Abstracts;

use Illuminate\Support\Collection;
use Notadd\Foundation\Routing\Traits\Helpers;

/**
 * Class Repository.
 */
abstract class Repository extends Collection
{
    use Helpers;

    /**
     * Initialize.
     *
     * @param \Illuminate\Support\Collection $collection
     */
    abstract public function initialize(Collection $collection);
}
