<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-06-01 15:29
 */
namespace Notadd\Foundation\Flow\Contracts;

use Notadd\Foundation\Flow\Flow;

/**
 * Interface SupportStrategy.
 */
interface SupportStrategy
{
    /**
     * @param Flow   $workflow
     * @param object $subject
     *
     * @return bool
     */
    public function supports(Flow $workflow, $subject);
}
