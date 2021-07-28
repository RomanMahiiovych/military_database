<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RankSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ranks')->insert([
            ['rank' => 'General' , 'created_at' => date('Y-m-d H:i:s')],
            ['rank' => 'Major', 'created_at' => date('Y-m-d H:i:s')],
            ['rank' => 'Captain', 'created_at' => date('Y-m-d H:i:s')],
            ['rank' => 'Lieutenant', 'created_at' => date('Y-m-d H:i:s')],
            ['rank' => 'Sergeant', 'created_at' => date('Y-m-d H:i:s')],
        ]);
    }
}
