<?php

namespace Imega\DataReporting\Models\Angus;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Imega\DataReporting\Models\Angus\Traits\BitwiseFlagTrait;
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
 * @property int $imega_status
 */
final class CsnAudit extends AngusModel
{
    use BitwiseFlagTrait;
    use QueryDateTrait;

    public const IMEGA_STATUS_APPROVED = 'approved';
    public const IMEGA_STATUS_COMPLETED = 'completed';
    public const IMEGA_STATUS_DECLINED = 'declined';
    public const IMEGA_STATUS_REFERRED = 'referred';

    public const IMEGA_STATUS_FLAG_APPROVED = 1 << 0;
    public const IMEGA_STATUS_FLAG_COMPLETED = 1 << 1;
    public const IMEGA_STATUS_FLAG_DECLINED = 1 << 2;
    public const IMEGA_STATUS_FLAG_REFERRED = 1 << 3;

    public static function getImegaFlagStatus(string $imegaStatus): int
    {
        return match ($imegaStatus) {
            CsnAudit::IMEGA_STATUS_APPROVED => CsnAudit::IMEGA_STATUS_FLAG_APPROVED,
            CsnAudit::IMEGA_STATUS_COMPLETED => CsnAudit::IMEGA_STATUS_FLAG_COMPLETED,
            CsnAudit::IMEGA_STATUS_DECLINED => CsnAudit::IMEGA_STATUS_FLAG_DECLINED,
            CsnAudit::IMEGA_STATUS_REFERRED => CsnAudit::IMEGA_STATUS_FLAG_REFERRED,
            default => 0,
        };
    }

    public function financeProvider(): BelongsTo
    {
        return $this->belongsTo(FinanceProvider::class);
    }

    /**
     * Scope a query to only include csns with specified status.
     *
     * @param EloquentBuilder $query
     * @param string $imegaStatus
     * @return EloquentBuilder
     */
    public function scopeStatus(EloquentBuilder $query, string $imegaStatus): EloquentBuilder
    {
        return $query->where('imega_status', '&', self::getImegaFlagStatus($imegaStatus));
    }

}
