<?php

namespace Imega\DataReporting\Models;

use Illuminate\Database\Eloquent\Model;

final class Order extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'data-reporting-orders';
}
