<?php

namespace Database\Factories\Angus;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Model;
use Imega\DataReporting\Models\Angus\Client;
use Imega\DataReporting\Models\Angus\FinanceProvider;

/**
 * @extends Factory<Client>
 */
class ClientFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<Model>
     */
    protected $model = Client::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'             => fake()->name,
            'alias'            => fake()->sentence,
            'api_key'          => fake()->md5,
            'api_secret'       => fake()->md5,
            'finance_provider' => FinanceProvider::all()->random(),
            'licence_status'   => config('data-reporting.client-statuses.ACTIVE'),
            'max_order_amount' => fake()->numberBetween(0, 99999),
            'min_order_amount' => 0,
            'test_mode'        => true,
        ];
    }
}
