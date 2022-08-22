<?php

namespace Imega\DataReporting\Models\RollUp;

use Illuminate\Database\Eloquent\Model;

abstract class RollUpModel extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'data-reporting-roll-up';
}
