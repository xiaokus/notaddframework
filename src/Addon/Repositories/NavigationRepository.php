<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-09-30 11:39
 */
namespace Notadd\Foundation\Addon\Repositories;

use Illuminate\Support\Collection;
use Notadd\Foundation\Http\Abstracts\Repository;

/**
 * Class NavigationRepository.
 */
class NavigationRepository extends Repository
{
    /**
     * Initialize.
     *
     * @param \Illuminate\Support\Collection $data
     */
    public function initialize(Collection $data)
    {
        if ($this->container->isInstalled()) {
            $this->items = $this->cache->tags('notadd')->rememberForever('addon.navigation.repository',
                function () use ($data) {
                    $collection = collect();
                    $data->each(function ($definition, $key) use ($collection) {
                        if (is_array($definition) && $definition) {
                            foreach ($definition as $item) {
                                $collection->push($item);
                            }
                        }
                    });

                    return $collection->all();
                });
        }
    }
}
