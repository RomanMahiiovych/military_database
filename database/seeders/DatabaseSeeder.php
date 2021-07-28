<?php

namespace Database\Seeders;

use App\Models\Soldier;
use App\Models\SoldierHierarchy;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create();

        $this->call([
            RankSeeder::class,
        ]);

        Soldier::factory(10)->create()->each(function ($soldier) {
            $level = ($soldier->rank_id != 1)
                ? $soldier->rank_id - 1
                : 1;

            $head = Soldier::where('rank_id', $level)->first();

            if ($head) {
                SoldierHierarchy::factory()->create([
                    'soldier_id' => $soldier->id,
                    'head_id' => $head->id,
                    'level' => $soldier->rank_id
                ]);
            }
        });

    }
}
