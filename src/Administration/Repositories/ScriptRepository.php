<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-09-27 14:23
 */
namespace Notadd\Foundation\Administration\Repositories;

use Illuminate\Support\Collection;
use Notadd\Foundation\Http\Abstracts\Repository;

/**
 * Class ScriptRepository.
 */
class ScriptRepository extends Repository
{
    /**
     * Initialize.
     *
     * @param \Illuminate\Support\Collection $collection
     */
    public function initialize(Collection $collection)
    {
        $this->module->assets()->filter(function ($definition) {
            return isset($definition['entry'])
                && isset($definition['type'])
                && $definition['entry'] == 'administration'
                && $definition['type'] == 'script';
        })->each(function ($definition) {
            $definition['file'] = $this->url->asset($definition['file']);
            $this->items[] = $definition;
        });
        $this->addon->assets()->filter(function ($definition) {
            return isset($definition['entry'])
                && isset($definition['type'])
                && $definition['entry'] == 'administration'
                && $definition['type'] == 'script';
        })->each(function ($definition) {
            $definition['file'] = $this->url->asset($definition['file']);
            $this->items[] = $definition;
        });
    }
}
