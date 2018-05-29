<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-09-14 20:58
 */
namespace Notadd\Foundation\Http\Bootstraps;

use Carbon\Carbon;
use Notadd\Foundation\Http\Contracts\Bootstrap;
use Notadd\Foundation\Http\Contracts\Detector;
//use Notadd\Foundation\Http\Detectors\ListenerDetector;
use Notadd\Foundation\Http\Detectors\CommandDetector;
use Notadd\Foundation\Http\Detectors\SubscriberDetector;
use Notadd\Foundation\Routing\Traits\Helpers;

/**
 * Class LoadDetect.
 */
class LoadDetection implements Bootstrap
{
    use Helpers;

    /**
     * @var array
     */
    protected $detectors = [
//        ListenerDetector::class,
        CommandDetector::class,
        SubscriberDetector::class,
    ];

    /**
     * Bootstrap the given application.
     */
    public function bootstrap()
    {
        if ($this->container->isInstalled() && $this->cache->store()->has('bootstrap.detection')) {
            $collection = $this->cache->store()->get('bootstrap.detection', collect());
        } else {
            $collection = collect();
            foreach ($this->detectors as $detector) {
                $detector = $this->container->make($detector);
                $detector instanceof Detector && collect($detector->paths())->each(function ($definition) use ($collection, $detector) {
                    collect($detector->detect($definition['path'], $definition['namespace']))->each(function ($subscriber) use ($collection) {
                        $collection->push($subscriber);
                    });
                });
            }
            $this->container->isInstalled() &&
            $this->cache->tags('notadd')->put('bootstrap.detection', $collection, (new Carbon())->addHour(10));
        }
        $collection->each(function ($subscriber) {
            $this->event->subscribe($subscriber);
        });
    }
}
