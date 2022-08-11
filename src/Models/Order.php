<?php

namespace Imega\DataReporting\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Imega\DataReporting\Requests\ListOrderRequest;

/**
 * Imega\DataReporting\Models
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

    public const JOB_TYPE_NEW_INSTALL = 1;
    public const JOB_TYPE_MIGRATION_UPGRADE = 2;
    public const JOB_TYPE_OTHER_TASK = 3;
    public const JOB_TYPE_BESPOKE_DEV = 4;
    public const JOB_TYPE_MULTI_LENDER = 5;

    public const STATUS_AWAITING_ACCOUNT_INFO = 1;
    public const STATUS_DETAILS_RECEIVED = 2;
    public const STATUS_SCHEDULED_AND_WAITING = 3;
    public const STATUS_INSTALLATION_IN_PROGRESS = 4;
    public const STATUS_DETAILS_INCOMPLETE_NOT_WORKING = 5;
    public const STATUS_ON_HOLD_LONG_TERM = 6;
    public const STATUS_AWAITING_TESTING_DOCUMENTATION = 7;
    public const STATUS_AWAITING_SECOND_APPROVAL = 8;
    public const STATUS_HOLDING_PLACE_FOR_RELEASE_TO_PRODUCTION = 9;
    public const STATUS_FINAL_CLEARUP_TASKS = 10;
    public const STATUS_ACTIVE = 11;
    public const STATUS_CANCELLED = 12;
    public const STATUS_MISC = 13;
    public const STATUS_HOLDING_AREA_FOR_DEVELOPMENT_TASKS = 14;
    public const STATUS_DEKO_DIRECT_INTEGRATIONS = 15;

    protected array $searchableJobTypes = [
        self::JOB_TYPE_NEW_INSTALL,
        self::JOB_TYPE_MIGRATION_UPGRADE,
        self::JOB_TYPE_OTHER_TASK,
        self::JOB_TYPE_BESPOKE_DEV,
        self::JOB_TYPE_MULTI_LENDER,
    ];

    protected array $searchableStatuses = [
        self::STATUS_AWAITING_ACCOUNT_INFO,
        self::STATUS_DETAILS_RECEIVED,
        self::STATUS_SCHEDULED_AND_WAITING,
        self::STATUS_INSTALLATION_IN_PROGRESS,
        self::STATUS_DETAILS_INCOMPLETE_NOT_WORKING,
        self::STATUS_ON_HOLD_LONG_TERM,
        self::STATUS_AWAITING_TESTING_DOCUMENTATION,
        self::STATUS_AWAITING_SECOND_APPROVAL,
        self::STATUS_HOLDING_PLACE_FOR_RELEASE_TO_PRODUCTION,
        self::STATUS_FINAL_CLEARUP_TASKS,
        self::STATUS_ACTIVE,
        self::STATUS_CANCELLED,
        self::STATUS_HOLDING_AREA_FOR_DEVELOPMENT_TASKS,
        self::STATUS_DEKO_DIRECT_INTEGRATIONS,
    ];

    public function getOrdersByFilter(ListOrderRequest $request): Collection
    {
        $qb = $this->newQuery()
            ->select(['orderid', 'invoiceid', 'company', 'finance', 'package', 'email', 'created', 'statusid'])
            ->statusNotTest();

        $plainWhereFilters = [
            'jobtype' => 'job_type',
        ];

        foreach ($plainWhereFilters as $column => $requestValue) {
            if ($request->$requestValue) {
                $qb->where($column, $request->$requestValue);
            }
        }

        if ($request->finance_provider) {
            $qb->where('finance', 'LIKE', Str::of($request->finance_provider)->wrap('%'));
        }

        if ($request->month) {
            $qb->whereMonth('created', $request->month);
        }

        if ($request->year) {
            $qb->whereYear('created', $request->year);
        }

        return $qb->orderBy('orderid', 'DESC')->get();
    }

    public function scopeStatusNotTest(EloquentBuilder $query): EloquentBuilder
    {
        return $query->whereNot('statusid', self::STATUS_MISC);
    }
}
