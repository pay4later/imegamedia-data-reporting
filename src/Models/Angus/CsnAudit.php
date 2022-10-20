<?php

namespace Imega\DataReporting\Models\Angus;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Imega\DataReporting\Traits\QueryDateTrait;

/**
 * Imega\DataReporting\Models\Angus\CsnAudit
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $finance_provider_id
 * @property string $ip_address
 * @property int $client_id
 * @property string $order_id
 * @property string $csn_status
 * @property string|null $platform_status
 * @property array $responses
 * @property string|null $imega_status
 */
final class CsnAudit extends AngusModel
{
    use QueryDateTrait;

    public function financeProvider(): BelongsTo
    {
        return $this->belongsTo(FinanceProvider::class);
    }

    /**
     * Scope a query to only include csns with specified statuses.
     *
     * @param EloquentBuilder $query
     * @param array $statuses
     * @return EloquentBuilder
     */
    public function scopeStatusOptions(EloquentBuilder $query, array $statuses): EloquentBuilder
    {
        return $query->whereIn('imega_status', $statuses);
    }

    public function calculateAcceptedRatePercentage(int $acceptedCsns, int $uniqueCsns): int
    {
        if ($acceptedCsns !== 0 && $uniqueCsns !== 0) {
            return ($acceptedCsns / $uniqueCsns) * 100;
        }

        return 0;
    }

}
