<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-16 19:03
 */
namespace Notadd\Foundation\Navigation\Handlers\Item;

use Notadd\Foundation\Navigation\Models\Item;
use Notadd\Foundation\Routing\Abstracts\Handler;

/**
 * Class CreateHandler.
 */
class CreateHandler extends Handler
{
    /**
     * Execute Handler.
     */
    public function execute()
    {
        if (Item::query()->create([
            'color'      => $this->request->input('color'),
            'enabled'    => $this->request->input('enabled'),
            'group_id'   => $this->request->input('group_id'),
            'icon_image' => $this->request->input('icon_image'),
            'link'       => $this->request->input('link'),
            'order_id'   => 0,
            'parent_id'  => 0,
            'target'     => $this->request->input('target'),
            'title'      => $this->request->input('title'),
            'tooltip'    => $this->request->input('tooltip'),
        ])) {
            $this->withCode(200)->withMessage('content::category.create.success');
        } else {
            $this->withCode(500)->withError('');
        }
    }
}
