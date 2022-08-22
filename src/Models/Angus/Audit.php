<?php

namespace Imega\DataReporting\Models\Angus;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Imega\DataReporting\Traits\QueryDateTrait;

/**
 * Imega\DataReporting\Models\Angus
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
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class Audit extends AngusModel
{
    use QueryDateTrait;

    public function clients(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'imegaid');
    }
}
