<?php

namespace Imega\DataReporting\Models\Angus;

use Illuminate\Database\Eloquent\Model;

abstract class AngusModel extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'data-reporting-angus';
}
