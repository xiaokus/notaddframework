<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-02-16 17:55
 */
namespace Notadd\Foundation\Navigation\Handlers\Group;

use Notadd\Foundation\Navigation\Models\Group;
use Notadd\Foundation\Routing\Abstracts\Handler;

/**
 * Class EditHandler.
 */
class EditHandler extends Handler
{
    /**
     * Execute Handler.
     *
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function execute()
    {
        $this->validate($this->request, [
            'title' => 'required',
            'alias' => 'required|alpha_dash|unique:menu_groups,' . $this->request->input('id'),
        ], [
            'alias.required' => '必须填写分组别名',
            'alias.alpha_dash' => '分组别名只能包含字母、数字、破折号（ - ）以及下划线（ _ ）',
            'alias.unique' => '分组别名已被占用',
            'title.required' => '必须填写分组标题',
        ]);
        $article = Group::query()->find($this->request->input('id'));
        if ($article && $article->update([
                'alias' => $this->request->input('alias'),
                'title' => $this->request->input('title'),
            ])) {
            $this->withCode(200)->withMessage('content::article.update.success');
        } else {
            $this->withCode(500)->withError('');
        }
    }
}
