<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2017, notadd.com
 * @datetime 2017-09-12 17:35
 */
namespace Notadd\Foundation\SearchEngine\Models;

use Notadd\Foundation\Database\Model;

/**
 * Class Rule.
 */
class Rule extends Model
{
    /**
     * @var array
     */
    protected $casts = [
        'open'  => 'boolean',
        'order' => 'integer',
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'module',
        'open',
        'order',
        'path',
        'template',
    ];

    /**
     * @var string
     */
    protected $table = 'seo_rules';
}
