<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-10-14 11:11
 */
namespace Notadd\Foundation\Member\Events;

use Notadd\Foundation\Member\Abstracts\Driver;
use Notadd\Foundation\Member\Member;

/**
 * Class MemberDeleted.
 */
class MemberDeleted
{
    /**
     * @var \Notadd\Foundation\Member\Member
     */
    protected $member;

    /**
     * MemberUpdated constructor.
     *
     * @param \Notadd\Foundation\Member\Member $member
     *
     * @internal param \Illuminate\Container\Container $container
     */
    public function __construct(Member $member)
    {
        $this->member = $member;
    }
}
