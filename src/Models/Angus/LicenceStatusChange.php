<?php

namespace Imega\DataReporting\Models\Angus;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Collection;
use Imega\DataReporting\Models\Angus\AngusModel;

final class LicenceStatusChange extends AngusModel
{

    public const REASON_IMEGA_MIGRATION = 'imega-migration';

    public function scopeReasonNotImegaMigration(EloquentBuilder $query): EloquentBuilder
    {
        return $query->whereNot('reason', self::REASON_IMEGA_MIGRATION);
    }
}
