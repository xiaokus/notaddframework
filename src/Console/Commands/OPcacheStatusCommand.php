<?php
/**
 * This file is part of Notadd.
 *
 * @author        TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime      2017-11-08 15:56
 */
namespace Notadd\Foundation\Console\Commands;

use Notadd\Foundation\Console\Abstracts\Command;

/**
 * Class OPcacheStatusCommand.
 */
class OPcacheStatusCommand extends Command
{
    /**
     * @var array
     */
    protected $hitsHeaders = [
        'Total',
        'Hits',
        'Misses',
        'Blacklist',
    ];

    /**
     * @var array
     */
    protected $keysHeaders = [
        'Total',
        'Free',
        'Scripts',
        'Wasted',
    ];

    /**
     * @var array
     */
    protected $memoryHeaders = [
        'Total',
        'Free',
        'Used',
        'Wasted',
    ];

    /**
     * @var array
     */
    protected $restartsHeaders = [
        'Total',
        'Manual',
        'Keys',
        'Memory',
    ];

    /**
     * @var string
     */
    protected $prefix = '';

    /**
     * OPcacheStatusCommand constructor.
     *
     * @param null $name
     */
    public function __construct($name = null)
    {
        parent::__construct($name);
        $this->prefix = function_exists('opcache_reset') ? 'opcache_' : (function_exists('accelerator_reset') ? 'accelerator_' : '');
    }

    /**
     * Configure Command.
     */
    public function configure()
    {
        $this->setDescription('Show OPcache status.');
        $this->setName('opcache:status');
    }

    /**
     * Command Handler.
     */
    public function handle()
    {
        $configurations = call_user_func($this->prefix . 'get_configuration');
        $status = call_user_func($this->prefix . 'get_status');
        $this->table($this->memoryHeaders, [
            [
                $configurations['directives']['opcache.memory_consumption'],
                $status['memory_usage']['free_memory'],
                $status['memory_usage']['used_memory'],
                $status['memory_usage']['wasted_memory'],
            ],
        ]);
        $this->table($this->keysHeaders, [
            [
                $status[$this->prefix . 'statistics']['max_cached_keys'],
                $status[$this->prefix . 'statistics']['max_cached_keys'] - $status[$this->prefix . 'statistics']['num_cached_keys'],
                $status[$this->prefix . 'statistics']['num_cached_scripts'],
                $status[$this->prefix . 'statistics']['num_cached_keys'] - $status[$this->prefix . 'statistics']['num_cached_scripts'],
            ],
        ]);
        $this->table($this->hitsHeaders, [
            [
                0,
                $status[$this->prefix . 'statistics']['hits'],
                $status[$this->prefix . 'statistics']['misses'],
                $status[$this->prefix . 'statistics']['blacklist_misses'],
            ],
        ]);
        $this->table($this->restartsHeaders, [
            [
                0,
                $status[$this->prefix . 'statistics']['manual_restarts'],
                $status[$this->prefix . 'statistics']['hash_restarts'],
                $status[$this->prefix . 'statistics']['oom_restarts'],
            ],
        ]);
    }
}
