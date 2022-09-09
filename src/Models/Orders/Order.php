<?php

namespace Imega\DataReporting\Models\Orders;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Imega\DataReporting\Enums\OrderJobType;
use Imega\DataReporting\Enums\OrderStatus;

/**
 * Imega\DataReporting\Models\Orders
 *
 * @property int $orderid
 * @property int $statusid
 * @property int $jobtype
 * @property int $ooh
 * @property string|null $company
 * @property string|null $firstname
 * @property string|null $lastname
 * @property string|null $tel
 * @property string|null $email
 * @property string|null $amemail
 * @property string|null $package
 * @property string|null $finance
 * @property string|null $module
 * @property string|null $comment
 * @property string|null $comment_private
 * @property string|null $installdate
 * @property string|null $testlivedate
 * @property string|null $prodlivedate
 * @property string|null $owner
 * @property string|null $clientid
 * @property string|null $invoiceid
 * @property string|null $invoicecreated
 * @property string $created
 */
final class Order extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'data-reporting-orders';

    public function scopeStatusNotTest(EloquentBuilder $query): EloquentBuilder
    {
        return $query->where('statusid', '<>', OrderStatus::Misc->value);
    }

    public function scopeJobType(EloquentBuilder $query, int $jobType): EloquentBuilder
    {
        if (!OrderJobType::tryFrom($jobType)) {
            return $query;
        }

        return $query->where('jobtype', $jobType);
    }

    public function scopeFinanceProvider(EloquentBuilder $query, string $financeProvider): EloquentBuilder
    {
        return $query->where('finance', 'LIKE', '%' . $financeProvider . '%');
    }
}
