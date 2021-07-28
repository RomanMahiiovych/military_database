<?php

namespace Database\Factories;

use App\Models\SoldierHierarchy;
use Illuminate\Database\Eloquent\Factories\Factory;

class SoldierHierarchyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SoldierHierarchy::class;


    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'soldier_id' => $this->faker->randomDigit(),
            'head_id' => $this->faker->randomDigit(),
            'level'   => $this->faker->randomDigit()
        ];
    }
}
