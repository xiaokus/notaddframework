<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-09-19 10:54
 */
namespace Notadd\Foundation\Extension\Repositories;

use Illuminate\Support\Collection;
use Notadd\Foundation\Extension\Extension;
use Notadd\Foundation\Http\Abstracts\Repository;

/**
 * Class ExpandRepository.
 */
class ExtensionRepository extends Repository
{
    /**
     * @var bool
     */
    protected $loadFromCache = true;

    /**
     * Initialize.
     *
     * @param \Illuminate\Support\Collection $data
     */
    public function initialize(Collection $data)
    {
        if ($this->container->isInstalled()) {
            $this->items = $this->cache->tags('notadd')->rememberForever('extension.repository', function () use ($data) {
                $collection = collect();
                $data->each(function ($directory, $index) use ($collection) {
                    $extension = new Extension([
                        'directory' => $directory,
                    ]);
                    if ($this->file->exists($file = $directory . DIRECTORY_SEPARATOR . 'composer.json')) {
                        $package = collect(json_decode($this->file->get($file), true));
                        $identification = data_get($package, 'name');
                        $extension->offsetSet('identification', $identification);
                        $extension->offsetSet('description', data_get($package, 'description'));
                        $extension->offsetSet('authors', data_get($package, 'authors'));
                        if ($package->get('type') == 'notadd-extension' && $extension->validate()) {
                            $autoload = collect([
                                $directory,
                                'vendor',
                                'autoload.php',
                            ])->implode(DIRECTORY_SEPARATOR);
                            if ($this->file->exists($autoload)) {
                                $extension->offsetSet('autoload', $autoload);
                                $this->file->requireOnce($autoload);
                                $this->loadFromCache = false;
                            }
                            collect(data_get($package, 'autoload.psr-4'))->each(function ($entry, $namespace) use (
                                $extension
                            ) {
                                $extension->offsetSet('namespace', $namespace);
                                $extension->offsetSet('service', $namespace . 'ExtensionServiceProvider');
                            });
                            $provider = $extension->offsetGet('service');
                            $extension->offsetSet('initialized', boolval(class_exists($provider) ?: false));
                            $key = 'extension.' . $identification . '.enabled';
                            $extension->offsetSet('enabled', boolval($this->setting->get($key, false)));
                            $key = 'extension.' . $identification . '.installed';
                            $extension->offsetSet('installed', boolval($this->setting->get($key, false)));
                            $install = 'extension.' . $identification . '.require.install';
                            $uninstall = 'extension.' . $identification . '.require.uninstall';
                            $extension->offsetSet('require', [
                                'install'   => boolval($this->setting->get($install, false)),
                                'uninstall' => boolval($this->setting->get($uninstall, false)),
                            ]);
                        }
                        $collection->put($extension->get('identification'), $extension);
                    }
                });

                return $collection->all();
            });
            if ($this->loadFromCache) {
                collect($this->items)->each(function (Extension $extension) {
                    if ($extension->offsetExists('autoload')) {
                        $autoload = $extension->get('autoload');
                        $this->file->exists($autoload) && $this->file->requireOnce($autoload);
                    }
                });
            }
        }
    }
}
