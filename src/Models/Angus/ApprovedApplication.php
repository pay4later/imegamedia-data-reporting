<?php

namespace Imega\DataReporting\Models\Angus;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * App\Models\ApprovedApplication
 *
 * @property int $id
 * @property string $application_ref
 * @property int $client_id
 * @property string $order_id
 * @property int $finance_provider_id
 * @property int|null $csn_audit_id
 * @property bool $payment_requested
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Client $client
 * @property-read FinanceProvider $financeProvider
 * @method static Builder|ApprovedApplication newModelQuery()
 * @method static Builder|ApprovedApplication newQuery()
 * @method static Builder|ApprovedApplication query()
 * @method static Builder|ApprovedApplication whereApplicationRef($value)
 * @method static Builder|ApprovedApplication whereClientId($value)
 * @method static Builder|ApprovedApplication whereCreatedAt($value)
 * @method static Builder|ApprovedApplication whereCsnAuditId($value)
 * @method static Builder|ApprovedApplication whereFinanceProviderId($value)
 * @method static Builder|ApprovedApplication whereId($value)
 * @method static Builder|ApprovedApplication whereOrderId($value)
 * @method static Builder|ApprovedApplication wherePaymentRequested($value)
 * @method static Builder|ApprovedApplication whereUpdatedAt($value)
 */
final class ApprovedApplication extends Model
{

    protected $casts = [
        'client_id'         => 'int',
        'csn_audit_id'      => 'int',
        'payment_requested' => 'bool',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function financeProvider(): BelongsTo
    {
        return $this->belongsTo(FinanceProvider::class);
    }
}
