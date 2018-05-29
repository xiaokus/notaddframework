<?php
/**
 * This file is part of Notadd.
 *
 * @author TwilRoad <heshudong@ibenchu.com>
 * @copyright (c) 2016, notadd.com
 * @datetime 2016-12-01 20:14
 */
namespace Notadd\Foundation\Database;

use Illuminate\Database\Eloquent\Model as EloquentModel;
use Notadd\Foundation\Database\Traits\HasSetters;
use Notadd\Foundation\Database\Traits\MacroRelation;

/**
 * Class Model.
 */
class Model extends EloquentModel
{
    use HasSetters, MacroRelation;
}
