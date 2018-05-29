<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <269044570@qq.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-06-01 15:30
 */
namespace Notadd\Foundation\Flow;

use Notadd\Foundation\Flow\Contracts\SupportStrategy;

/**
 * Class ClassInstanceSupportStrategy.
 */
class ClassInstanceSupportStrategy implements SupportStrategy
{
    /**
     * @var string
     */
    private $className;

    /**
     * @param string $className a FQCN
     */
    public function __construct($className)
    {
        $this->className = $className;
    }

    /**
     * @param Flow   $workflow
     * @param object $subject
     *
     * @return bool
     */
    public function supports(Flow $workflow, $subject)
    {
        return $subject instanceof $this->className;
    }
}
