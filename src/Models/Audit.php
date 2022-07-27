<?php

namespace Imega\DataReporting\Models;

use Illuminate\Database\Eloquent\Model;

final class Audit extends Model
{
    /**
     * @var array<string>
     */
    protected $fillable = [
        'imegaid',
        'username',
        'usersite',
        'retailer',
        'version',
        'orderid',
        'action',
        'orderdesc',
        'orderamount',
        'rate',
        'deposit',
        'url',
        'path',
        'form',
        'browser',
        'ip',
        'error',
        'appurlparams',
        'auditkey',
    ];

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'data-reporting-source';
}
