<?php

namespace Imega\DataReporting\Models;

final class ReportLiveClient extends AngusModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'sampled_at',
        'finance_provider',
        'total_active',
        'total_billable',
        'total_inactive',
        'total_active_live',
        'total_active_test',
        'total_active_nodemo_live',
        'total_active_nodemo_test',
    ];
}
