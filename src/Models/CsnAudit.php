<?php

namespace Imega\DataReporting\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Imega\DataReporting\Traits\QueryDateTrait;

final class CsnAudit extends AngusModel
{
    use QueryDateTrait;

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
        'responses',
    ];

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
        return CsnAudit::selectRaw('COUNT(id)')
            ->whereColumn('finance_provider_id', 'ca1.finance_provider_id')
            ->createdLastHour()
            ->where(static fn($query) => $query
                ->whereIn('id', CsnAudit::selectRaw('MAX(id)')->createdLastHour()->groupBy('order_id'))
            )
            ->getQuery();
    }

    /**
     * @return QueryBuilder
     */
    public static function totalUniqueAcceptedCsns(): QueryBuilder
    {
        return CsnAudit::selectRaw('COUNT(id)')
            ->whereColumn('finance_provider_id', 'ca1.finance_provider_id')
            ->createdLastHour()
            ->statusOptions(config('data-reporting.approved-statuses'))
            ->getQuery();
    }

    public function calculateAcceptedRatePercentage(int $acceptedCsns, int $uniqueCsns): int
    {
        if ($acceptedCsns !== 0 && $uniqueCsns !== 0) {
            return ($acceptedCsns / $uniqueCsns) * 100;
        }

        return 0;
    }

}
