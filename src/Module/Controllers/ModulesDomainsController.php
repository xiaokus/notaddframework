<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-09-30 16:03
 */
namespace Notadd\Foundation\Module\Controllers;

use Illuminate\Support\Collection;
use Notadd\Foundation\Routing\Abstracts\Controller;
use Notadd\Foundation\Validation\Rule;

/**
 * Class ModulesDomainsController.
 */
class ModulesDomainsController extends Controller
{
    /**
     * @var bool
     */
    protected $onlyValues = true;

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function update()
    {
        list($data) = $this->validate($this->request, [
            'data' => Rule::required(),
        ], [
            'data.required' => '域名数据必须填写',
        ]);
        $collection = collect();
        collect($data)->each(function ($definition) use ($collection) {
            if (isset($definition['identification'])
                && $this->module->has($definition['identification'])
                && $this->module->installed()->has($definition['identification']) || in_array($definition['identification'], [
                    'notadd/administration',
                    'notadd/api',
                    'notadd/notadd',
                ])) {
                $identification = $definition['identification'];
                $alias = 'module.' . $identification . '.domain.alias';
                $enabled = 'module.' . $identification . '.domain.enabled';
                $host = 'module.' . $identification . '.domain.host';
                $this->setting->set($alias, data_get($definition, 'alias', ''));
                $this->setting->set($enabled, data_get($definition, 'enabled', false));
                $this->setting->set($host, data_get($definition, 'host', ''));
                if (data_get($definition, 'default', false)) {
                    $this->setting->set('module.default', $identification);
                }
                $collection->put($identification, [
                    'alias' => data_get($definition, 'alias', ''),
                    'default' => data_get($definition, 'default', false),
                    'enabled' => data_get($definition, 'enabled', ''),
                    'host' => data_get($definition, 'host', ''),
                ]);
            }
        });
        $collection->isNotEmpty() && $this->writeToConfigurationFile($collection);

        return $this->response->json([
            'message' => '更新模块域名信息成功！',
        ]);
    }

    /**
     * @param \Illuminate\Support\Collection $collection
     */
    protected function writeToConfigurationFile(Collection $collection)
    {
        file_put_contents($this->container->staticPath('configuration.json'), json_encode($collection->toArray()));
    }
}
