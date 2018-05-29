<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-16 17:56
 */
namespace Notadd\Foundation\Navigation\Handlers\Group;

use Notadd\Foundation\Navigation\Models\Group;
use Notadd\Foundation\Routing\Abstracts\Handler;

/**
 * Class DeleteHandler.
 */
class DeleteHandler extends Handler
{
    /**
     * Execute Handler.
     */
    public function execute()
    {
        $category = Group::query()->find($this->request->input('id'));
        if ($category && $category->delete()) {
            $this->withCode(200)->withMessage('content::category.delete.success');
        } else {
            $this->withCode(500)->withError('');
        }
    }
}
