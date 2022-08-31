<?php

namespace Imega\DataReporting\Models\Angus;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Imega\DataReporting\Models\Angus\LicenceStatusChange
 *
 * @property int $id
 * @property int $client_id
 * @property string $from
 * @property string $to
 * @property int $finance_provider_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Client $client
 * @property-read FinanceProvider $financeProvider
 */
final class LicenceStatusChange extends AngusModel
{
    public const REASON_IMEGA_MIGRATION = 'imega-migration';

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function financeProvider(): BelongsTo
    {
        return $this->belongsTo(FinanceProvider::class);
    }

    public function scopeReasonNotImegaMigration(EloquentBuilder $query): EloquentBuilder
    {
        return $query->whereNot('reason', self::REASON_IMEGA_MIGRATION);
    }
}
