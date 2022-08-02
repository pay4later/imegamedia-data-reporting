<?php

namespace Imega\DataReporting\Models;

final class Audit extends AngusModel
{

    /**
     * The attributes that are mass assignable.
     *
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
}
