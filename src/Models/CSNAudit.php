<?php

namespace Imega\DataReporting\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;

final class CSNAudit extends AngusModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'finance_provider_id',
        'ip_address',
        'client_id',
        'order_id',
        'csn_status',
        'platform_status',
        'responses'
    ];

    protected $table = 'csn_audits';

    /**
     * Scope a query to only include csns created today.
     *
     * @param EloquentBuilder $query
     * @return EloquentBuilder
     */
    public function scopeCreatedLastHour(EloquentBuilder $query): EloquentBuilder
    {
        return $query->where('created_at', Carbon::now()->today()->toDateTimeString());
    }

    /**
     * Scope a query to only include csns with specified statuses.
     *
     * @param EloquentBuilder $query
     * @return EloquentBuilder
     */
    public function scopeStatusOptions(EloquentBuilder $query, array $statuses): EloquentBuilder
    {
        return $query->whereIn('csn_status', $statuses);
    }

    /**
     * @return QueryBuilder
     */
    public static function totalUniqueCsns(): QueryBuilder
    {
        return CSNAudit::selectRaw('COUNT(id)')
            ->whereColumn('finance_provider_id', 'ca1.finance_provider_id')
            ->createdLastHour()
            ->where(static fn ($query) => $query
                ->whereIn('id', CSNAudit::selectRaw('MAX(id)')->createdLastHour()->groupBy('order_id'))
            )
            ->getQuery();
    }

    /**
     * @return QueryBuilder
     */
    public static function totalUniqueAcceptedCsns(): QueryBuilder
    {
        return CSNAudit::selectRaw('COUNT(id)')
            ->whereColumn('finance_provider_id', 'ca1.finance_provider_id')
            ->createdLastHour()
            ->where(static fn ($query) => $query
                ->whereIn('id', CSNAudit::selectRaw('MAX(id)')->createdLastHour()->groupBy('order_id'))
            )
            ->statusOptions(config('data-reporting.approved-statuses'))
            ->getQuery();
    }

}
