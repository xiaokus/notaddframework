<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-09-13 12:53
 */
namespace Notadd\Foundation\SearchEngine\Handlers;

use Illuminate\Support\Str;
use Notadd\Foundation\Routing\Abstracts\Handler;
use Notadd\Foundation\SearchEngine\Models\Rule as SearchEngineRule;
use Notadd\Foundation\Validation\Rule;

/**
 * Class BatchHandler.
 */
class BatchHandler extends Handler
{
    /**
     * @var bool
     */
    protected $onlyValues = true;

    /**
     * Execute Handler.
     *
     * @throws \Exception
     */
    protected function execute()
    {
        list($add, $delete, $edit) = $this->validate($this->request, [
            'add'          => Rule::array(),
            'add.*.order'  => [
                Rule::numeric(),
                Rule::required(),
            ],
            'add.*.path'   => Rule::required(),
            'delete'       => Rule::array(),
            'delete.*.id'  => [
                Rule::numeric(),
                Rule::required(),
            ],
            'edit'         => Rule::array(),
            'edit.*.order' => [
                Rule::numeric(),
                Rule::required(),
            ],
            'edit.*.path'  => Rule::required(),
        ], [
            'add.*.order.numeric'    => '新增数据的匹配排序必须为数值',
            'add.*.order.required'   => '新增数据的匹配排序必须填写',
            'add.*.module.required'  => '新增数据的模块必须填写',
            'add.*.path.required'    => '新增数据的路由必须填写',
            'add.array'              => '新增数据必须为数组',
            'delete.*.id.numeric'    => '删除数据的 ID 必须为数值',
            'delete.*.id.required'   => '删除数据的 ID 必须填写',
            'delete.array'           => '删除数据必须为数组',
            'edit.*.order.numeric'   => '编辑数据的匹配排序必须为数值',
            'edit.*.order.required'  => '编辑数据的匹配排序必须填写',
            'edit.*.module.required' => '编辑数据的模块排序必须填写',
            'edit.*.path.required'   => '编辑数据的路由必须填写',
            'edit.array'             => '编辑数据必须为数组',
        ]);
        $this->transaction(function () use ($add, $delete, $edit) {
            collect($add)->each(function ($definition) {
                isset($definition['module']) && $definition['module'] = Str::replaceFirst('-', '/', $definition['module']);
                SearchEngineRule::query()->create($definition);
            });
            collect($delete)->each(function ($definition) {
                $rule = SearchEngineRule::query()->find($definition['id']);
                $rule instanceof SearchEngineRule && $rule->delete();
            });
            collect($edit)->each(function ($definition) {
                isset($definition['module']) && $definition['module'] = Str::replaceFirst('-', '/', $definition['module']);
                $rule = SearchEngineRule::query()->find($definition['id']);
                $rule instanceof SearchEngineRule && $rule->update($definition);
            });
            $this->withCode(200)->withMessage('批量更新数据成功！');
        });
    }
}
