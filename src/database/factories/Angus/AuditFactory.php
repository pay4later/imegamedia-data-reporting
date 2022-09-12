<?php

namespace Database\Factories\Angus;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Imega\DataReporting\Models\Angus\Audit;
use Imega\DataReporting\Models\Angus\Client;

/**
 * @extends Factory<Audit>
 */
class AuditFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Model>
     */
    protected $model = Audit::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'imegaid'     => Client::factory(),
            'orderamount' => fake()->numberBetween(0, 100),
        ];
    }
}
