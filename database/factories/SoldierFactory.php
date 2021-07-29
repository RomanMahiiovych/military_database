<?php


namespace Database\Factories;


use App\Models\Rank;
use App\Models\Soldier;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'rank_id' => Rank::all()->random()->id,
            'image' => $this->faker->imageUrl( 200,200, 'people'),
            'small_image' => $this->faker->imageUrl(200,200, 'people'),
            'type' => 'url'
        ];
    }

}
