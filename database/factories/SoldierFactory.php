<?php


namespace Database\Factories;


use App\Models\Soldier;
use Illuminate\Database\Eloquent\Factories\Factory;
use Psy\Util\Str;

class SoldierFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Soldier::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'date_of_entry' => $this->faker->dateTimeThisDecade(),
            'phone_number' => $this->faker->phoneNumber(),
            'email' => $this->faker->unique()->safeEmail(),
            'salary' => $this->faker->numberBetween(1000, 5000),
        ];
    }

}
