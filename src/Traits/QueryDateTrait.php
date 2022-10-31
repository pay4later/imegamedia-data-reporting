<?php

namespace Imega\DataReporting\Traits;

use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

trait QueryDateTrait
{
    /**
     * Scope a query to query date between.
     *
     * @param EloquentBuilder $query
     * @param string $fieldName
     * @param CarbonInterface $start
     * @param CarbonInterface $end
     * @return EloquentBuilder
     */
    public function scopeCreatedBetween(EloquentBuilder $query, CarbonInterface $start, CarbonInterface $end, string $fieldName = 'created_at'): EloquentBuilder
    {
        return $query->whereBetween($fieldName, [$start, $end]);
    }

    /**
     * Scope a query to query today.
     *
     * @param EloquentBuilder $query
     * @param string $fieldName
     * @return EloquentBuilder
     */
    public function scopeCreatedToday(EloquentBuilder $query, string $fieldName = 'created_at'): EloquentBuilder
    {
        return $query->where($fieldName, Carbon::today());
    }
}
