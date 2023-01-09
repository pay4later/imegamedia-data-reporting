<?php

namespace Imega\DataReporting\Models\RollUp;

use Carbon\Carbon;

/**
 * Imega\DataReporting\Models\RollUp\LiveMerchant
 *
 * @property int $total_active_live
 * @property int $total_active_test
 * @property Carbon $sampled_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
final class LiveMerchant extends RollUpModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'sampled_at',
        'total_active_live',
        'total_active_test',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'total_active_live' => 'integer',
        'total_active_test' => 'integer',
        'sampled_at'        => 'datetime',
    ];
}
