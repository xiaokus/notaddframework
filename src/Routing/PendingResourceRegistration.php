<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-09-25 10:55
 */
namespace Notadd\Foundation\Routing;

use Illuminate\Routing\PendingResourceRegistration as IlluminatePendingResourceRegistration;

/**
 * Class PendingResourceRegistration.
 */
class PendingResourceRegistration extends IlluminatePendingResourceRegistration
{
    /**
     * @param array $methods
     *
     * @return $this
     */
    public function methods(array $methods)
    {
        $this->options['methods'] = $methods;

        return $this;
    }
}
