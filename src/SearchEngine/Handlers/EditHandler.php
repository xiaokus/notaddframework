<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-09-12 12:53
 */
namespace Notadd\Foundation\SearchEngine\Handlers;

use Illuminate\Container\Container;
use Notadd\Foundation\Module\ModuleManager;
use Notadd\Foundation\Routing\Abstracts\Handler;
use Notadd\Foundation\SearchEngine\Models\Rule as SearchEngineRule;
use Notadd\Foundation\Validation\Rule;

/**
 * Class EditHandler.
 */
class EditHandler extends Handler
{
    /**
     * @var bool
     */
    protected $onlyValues = true;

    /**
     * @var \Notadd\Foundation\Module\ModuleManager
     */
    protected $module;

    /**
     * EditHandler constructor.
     *
     * @param \Illuminate\Container\Container         $container
     * @param \Notadd\Foundation\Module\ModuleManager $module
     */
    public function __construct(Container $container, ModuleManager $module)
    {
        parent::__construct($container);
        $this->module = $module;
    }

    /**
     * Execute Handler.
     *
     * @throws \Exception
     */
    protected function execute()
    {
        list($id, $module, $template) = $this->validate($this->request, [
            'id'       => [
                Rule::numeric(),
                Rule::required(),
            ],
            'module'   => Rule::required(),
            'template' => Rule::required(),
        ], [
            'id.numeric'        => '规则 ID 必须为数值',
            'id.required'       => '规则 ID 必须填写',
            'module.required'   => '模块必须填写',
            'template.required' => '规则模板必须填写',
        ]);
        if ($this->module->has($module) || $module == 'global') {
            $rule = SearchEngineRule::query()->where('module', $module)->where('id', $id)->first();
            if ($rule instanceof SearchEngineRule && $rule->update([
                    'template' => $template,
                ])) {
                $this->withCode(200)->withMessage('更新规则信息成功！');
            } else {
                $this->withCode(500)->withError('模块下找不到对应的规则！');
            }
        } else {
            $this->withCode(500)->withError('模块不存在！');
        }
    }
}
