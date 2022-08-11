<?php

namespace Imega\DataReporting\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Imega\DataReporting\Models\Order;

/**
 * @property-read int|null $month
 * @property-read int|null $year
 * @property-read string|null $finance_provider
 * @property-read int|null $job_type
 * @mixin Order
 */
final class ListOrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'finance_provider' => ['sometimes', 'nullable', 'string'],
            'job_type'         => ['sometimes', 'nullable', 'integer'],
            'month'            => ['sometimes', 'nullable', 'integer', 'between:1,12'],
            'year'             => ['sometimes', 'nullable', 'integer'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'finance_provider' => $this->finance_provider !== 'ALL' ? $this->finance_provider : null,
            'job_type'         => $this->job_type !== -1 ? $this->job_type : null,
            'month'            => $this->month ?? Carbon::now()->format('n'),
            'year'             => $this->year ?? Carbon::now()->format('Y'),
        ]);
    }
}
