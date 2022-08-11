<?php

namespace Imega\DataReporting\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

trait QueryDateTrait
{
    /**
     * Scope a query to query last hour.
     *
     * @param EloquentBuilder $query
     * @param string $fieldName
     * @return EloquentBuilder
     */
    public function scopeCreatedLastHour(EloquentBuilder $query, string $fieldName = 'created_at'): EloquentBuilder
    {
        return $query->where(function (EloquentBuilder $query) use ($fieldName) {
            $query
                ->where($fieldName, '>=', Carbon::now()
                    ->subHours()
                    ->setMinutes(0)
                    ->setSeconds(0)
                )
                ->where($fieldName, '<=', Carbon::now()
                    ->subHours()
                    ->setMinutes(59)
                    ->setSeconds(59)
                );
        });
    }
}
