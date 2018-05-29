<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-09-18 18:07
 */
namespace Notadd\Foundation\Addon\Repositories;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Notadd\Foundation\Http\Abstracts\Repository;

/**
 * Class AssetsRepository.
 */
class AssetsRepository extends Repository
{
    /**
     * Initialize.
     *
     * @param \Illuminate\Support\Collection $data
     */
    public function initialize(Collection $data)
    {
        if ($this->container->isInstalled()) {
            $this->items = $this->cache->tags('notadd')->rememberForever('addon.assets.repository', function () use ($data) {
                $collection = collect();
                $data->each(function ($items, $module) use ($collection) {
                    $items = collect($items);
                    $items->count() && $items->each(function ($items, $entry) use ($collection, $module) {
                        $items = collect($items);
                        $items->count() && $items->each(function ($definition, $identification) use ($collection, $entry, $module) {
                            $data = [
                                'entry'          => $entry,
                                'for'            => 'addon',
                                'identification' => $identification,
                                'module'         => $module,
                                'permission'     => data_get($definition, 'permission', ''),
                            ];
                            collect((array)data_get($definition, 'scripts'))->each(function ($path) use ($collection, $data) {
                                $collection->push(array_merge($data, [
                                    'file' => $path,
                                    'type' => 'script',
                                ]));
                            });
                            collect((array)data_get($definition, 'stylesheets'))->each(function ($path) use ($collection, $data) {
                                $collection->push(array_merge($data, [
                                    'file' => $path,
                                    'type' => 'stylesheet',
                                ]));
                            });
                        });
                    });
                });

                return $collection->all();
            });
        }
    }
}
