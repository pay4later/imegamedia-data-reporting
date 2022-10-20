<?php

namespace Database\Factories\Angus;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Imega\DataReporting\Models\Angus\CsnAudit;

/**
 * @extends Factory<CsnAudit>
 */
class CsnAuditFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Model>
     */
    protected $model = CsnAudit::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [

        ];
    }
}
