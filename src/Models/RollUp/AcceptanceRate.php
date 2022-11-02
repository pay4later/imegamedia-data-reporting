<?php

namespace Imega\DataReporting\Models\RollUp;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Imega\DataReporting\Models\Angus\FinanceProvider;

/**
 * Imega\DataReporting\Models\RollUp\AcceptanceRate
 *
 * @property int $client_id
 * @property int $finance_provider_id
 * @property int $acceptance_rate
 * @property int $total_unique_csns
 * @property int $total_unique_accepted_csns
 * @property Carbon $sampled_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class AcceptanceRate extends RollUpModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'sampled_at',
        'client_id',
        'finance_provider_id',
        'total_unique_declined_csns',
        'total_unique_accepted_csns',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'total_unique_declined_csns' => 'integer',
        'total_unique_accepted_csns' => 'integer',
        'sampled_at'                 => 'datetime',
    ];

    public function financeProvider(): BelongsTo
    {
        return $this->belongsTo(FinanceProvider::class);
    }

    public function calculateAcceptedRatePercentage(int $acceptedCsns, int $declinedCsns): int
    {
        if ($acceptedCsns !== 0 || $declinedCsns !== 0) {
            return ($acceptedCsns / ($acceptedCsns + $declinedCsns)) * 100;
        }

        return 0;
    }
}
