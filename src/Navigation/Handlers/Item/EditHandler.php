<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-16 17:56
 */
namespace Notadd\Foundation\Navigation\Handlers\Item;

use Notadd\Foundation\Navigation\Models\Item;
use Notadd\Foundation\Routing\Abstracts\Handler;

/**
 * Class EditHandler.
 */
class EditHandler extends Handler
{
    /**
     * Execute Handler.
     */
    public function execute()
    {
        $article = Item::query()->find($this->request->input('id'));
        if ($article && $article->update([
                'color'      => $this->request->input('color'),
                'enabled'    => $this->request->input('enabled'),
                'group_id'   => $this->request->input('group_id'),
                'icon_image' => $this->request->input('icon_image'),
                'link'       => $this->request->input('link'),
                'order_id'   => $this->request->input('order_id'),
                'parent_id'  => $this->request->input('parent_id'),
                'target'     => $this->request->input('target'),
                'title'      => $this->request->input('title'),
                'tooltip'    => $this->request->input('tooltip'),
            ])
        ) {
            $this->withCode(200)->withMessage('content::article.update.success');
        } else {
            $this->withCode(500)->withError('');
        }
    }
}
