<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-09-12 12:45
 */
namespace Notadd\Foundation\SearchEngine\Handlers;

use Illuminate\Container\Container;
use Illuminate\Support\Str;
use Notadd\Foundation\Module\ModuleManager;
use Notadd\Foundation\Routing\Abstracts\Handler;
use Notadd\Foundation\SearchEngine\Models\Rule as SeoRule;
use Notadd\Foundation\Validation\Rule;

/**
 * Class ListHandler.
 */
class ListHandler extends Handler
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
     * ListHandler constructor.
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
        list($identification) = $this->validate($this->request, [
            'identification' => Rule::required(),
        ], [
            'identification.required' => '模块标识必须填写',
        ]);
        $identification = Str::replaceFirst('-', '/', $identification);
        if ($this->module->has($identification) || $identification == 'global') {
            $builder = SeoRule::query();
            $builder->orderBy('order', 'asc');
            $builder->where('module', $identification);
            $this->withCode(200)->withData($builder->get())->withExtra([
                'module' => $identification == 'global' ? '全局' : $this->module->get($identification)->get('name'),
            ])->withMessage('获取数据成功！');
        } else {
            $this->withCode(500)->withError('模块不存在！');
        }
    }
}
