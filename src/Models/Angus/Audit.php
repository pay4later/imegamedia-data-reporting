<?php

namespace Imega\DataReporting\Models\Angus;

use Carbon\Carbon;
use Database\Factories\Angus\AuditFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Imega\DataReporting\Traits\QueryDateTrait;

/**
 * Imega\DataReporting\Models\Angus\Audit
 *
 * @property int $id
 * @property int|null $imegaid
 * @property string|null $username
 * @property string $usersite
 * @property string|null $retailer
 * @property string|null $version
 * @property string|null $orderid
 * @property string|null $action
 * @property string $orderdesc
 * @property string|null $orderamount
 * @property string|null $rate
 * @property string|null $deposit
 * @property string|null $url
 * @property string|null $error
 * @property string|null $path
 * @property string $form
 * @property string|null $appurlparams
 * @property string|null $auditkey
 * @property int $second_chance_attempts
 * @property string|null $browser
 * @property string|null $ip
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class Audit extends AngusModel
{
    use HasFactory;
    use QueryDateTrait;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'retailer' => 'int',
    ];

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return AuditFactory::new();
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'imegaid');
    }

    public function financeProvider(): BelongsTo
    {
        return $this->belongsTo(FinanceProvider::class, 'retailer');
    }
}
