<?php

namespace Imega\DataReporting\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

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

    /**
     * Scope a query to join clients table.
     *
     * @param EloquentBuilder $query
     * @return EloquentBuilder
     */
    public function scopeJoinClients(EloquentBuilder $query): EloquentBuilder
    {
        return $query->join('clients',  'a1.imegaid', '=', 'clients.id');
    }

    /**
     * Scope a query to only include live clients.
     *
     * @param EloquentBuilder $query
     * @return EloquentBuilder
     */
    public function scopeClientsTestMode(EloquentBuilder $query, bool $type): EloquentBuilder
    {
        return $query->where('clients.test_mode', $type);
    }

    /**
     * Scope a query to only include audits created today.
     *
     * @param EloquentBuilder $query
     * @return EloquentBuilder
     */
    public function scopeCreatedToday(EloquentBuilder $query): EloquentBuilder
    {
        return $query->whereDate('a1.created_at', Carbon::today());
    }
}
