<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DeskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // for ($i=1; $i <= 40; $i++) { 
        //     $desk = \App\Models\Desk::create([
        //         'zone_id' => 1,
        //         'desk_name' => "D".$i."A",
        //         'status' => 1,
        //         'reservation_id' => null
        //     ]);
        //     $desk = \App\Models\Desk::create([
        //         'zone_id' => 1,
        //         'desk_name' => "D".$i."B",
        //         'status' => 1,
        //         'availability' => false,
        //         'reservation_id' => null
        //     ]);
        // }
        // for ($i=1; $i <= 15; $i++) { 
        //     $desk = \App\Models\Desk::create([
        //         'zone_id' => 2,
        //         'desk_name' => "MR".$i,
        //         'status' => 1,
        //         'reservation_id' => null
        //     ]);
        // }
        for ($i=81; $i <= 86; $i++) { 
            for ($j=1; $j <= 4 ; $j++) { 
                $desk = \App\Models\Desk::create([
                    'zone_id' => 1,
                    'has_parent' => 1,
                    'parent_id' => $i,
                    'desk_name' => "MRD".$j,
                    'status' => 1,
                    'reservation_id' => null
                ]);
            }
        }
    }
}
