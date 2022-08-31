<?php

namespace Imega\DataReporting\Models\RollUp;

use Carbon\Carbon;

/**
 * Imega\DataReporting\Models\RollUp\LiveClient
 *
 * @property int $finance_provider_id
 * @property int $total_active
 * @property int $total_billable
 * @property int $total_inactive
 * @property int $total_active_live
 * @property int $total_active_test
 * @property int $total_active_no_demo_live
 * @property int $total_active_no_demo_test
 * @property Carbon $sampled_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class LiveClient extends RollUpModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'sampled_at',
        'finance_provider_id',
        'total_active',
        'total_billable',
        'total_inactive',
        'total_active_live',
        'total_active_test',
        'total_active_nodemo_live',
        'total_active_nodemo_test',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'total_active'              => 'integer',
        'total_billable'            => 'integer',
        'total_inactive'            => 'integer',
        'total_active_live'         => 'integer',
        'total_active_test'         => 'integer',
        'total_active_no_demo_live' => 'integer',
        'total_active_no_demo_test' => 'integer',
        'sampled_at'                => 'datetime',
    ];
}
