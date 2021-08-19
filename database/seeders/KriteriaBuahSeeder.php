<?php

namespace Database\Seeders;

use App\Models\KriteriaBuah;
use Illuminate\Database\Seeder;

class KriteriaBuahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // run seeder without event
        KriteriaBuah::withoutEvents(function () {
            // php faker
            $faker = \Faker\Factory::create();

            $data = [
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                    'uuid' => $faker->uuid(),
                    'name' => 'matang',
                    'created_by' => 1,
                    'updated_by' => 1,
                    'deleted_by' => null,
                ],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                    'uuid' => $faker->uuid(),
                    'name' => 'lewat matang',
                    'created_by' => 1,
                    'updated_by' => 1,
                    'deleted_by' => null,
                ],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                    'uuid' => $faker->uuid(),
                    'name' => 'busuk',
                    'created_by' => 1,
                    'updated_by' => 1,
                    'deleted_by' => null,
                ],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                    'uuid' => $faker->uuid(),
                    'name' => 'tangkai panjang',
                    'created_by' => 1,
                    'updated_by' => 1,
                    'deleted_by' => null,
                ],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                    'uuid' => $faker->uuid(),
                    'name' => 'buah keras',
                    'created_by' => 1,
                    'updated_by' => 1,
                    'deleted_by' => null,
                ],
            ];

            KriteriaBuah::insert($data);
        });
    }
}
