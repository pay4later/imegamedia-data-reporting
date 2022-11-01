<?php

namespace Imega\DataReporting\Models\RollUp;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Imega\DataReporting\Models\Angus\FinanceProvider;

/**
 * Imega\DataReporting\Models\RollUp\TotalApplication
 *
 * @property int $finance_provider_id
 * @property int $count
 * @property float $value
 * @property Carbon $sampled_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class TotalApplication extends RollUpModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'sampled_at',
        'finance_provider_id',
        'count',
        'value',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'count'      => 'integer',
        'value'      => 'float',
        'sampled_at' => 'datetime',
    ];

    public function financeProvider(): BelongsTo
    {
        return $this->belongsTo(FinanceProvider::class);
    }
}
