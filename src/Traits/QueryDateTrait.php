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
        return $query->where($fieldName, '>', Carbon::now()->subHours());
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
