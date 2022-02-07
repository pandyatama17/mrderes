<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    //     $reservation = \App\Models\Reservation::create([
    //         'user_id' => 3,
    //         'supervisor_id' => 1,
    //         'reservation_time' => \Carbon\Carbon::now(),
    //         'duration' => 10
    //     ]);
        // $reservation2 = \App\Models\Reservation::create([
        //     'desk_id' => 10,
        //     'user_id' => 3,
        //     'supervisor_id' => 1,
        //     'reservation_time' => \Carbon\Carbon::now()->hour(7)->minute(0)->second(0),
        //     'approved'=>true,
        //     'duration' => 10
        // ]);
    //     $reservation3 = \App\Models\Reservation::create([
    //         'user_id' => 3,
    //         'supervisor_id' => 1,
    //         'admin_id' => 2,
    //         'reservation_time' => \Carbon\Carbon::now(),
    //         'approved'=>true,
    //         'start_time'=>\Carbon\Carbon::now(),
    //         'duration' => 3
    //     ]);
    //     $reservationHist = \App\Models\ReservationHistory::create([
    //         'user_id' => 3,
    //         'supervisor_id' => 1,
    //         'admin_id' => 2,
    //         'reservation_time' => \Carbon\Carbon::now(),
    //         'start_time'=>\Carbon\Carbon::now(),
    //         'finish_time' => \Carbon\Carbon::now(),
    //         'duration' => 3
    //     ]);
    // }
        $ticket = \App\Models\Ticket::create([
            'user_id' => 3,
            'datetime'=>\Carbon\Carbon::parse('2022-01-01 12:00:00'),
            'type' => 'D',
            'duration' => 2,
            'status' => 1
         ]);
      //    $ticket = \App\Models\Ticket::create([
      //       'user_id' => 3,
      //       'datetime'=>\Carbon\Carbon::now()->addDays(1)->hour(15),
      //       'type' => 'MR',
      //       'duration' => 2,
      //       'status' => 1
      //    ]);
      //    $ticket = \App\Models\Ticket::create([
      //       'user_id' => 3,
      //       'datetime'=>\Carbon\Carbon::now()->addDays(1)->hour(8),
      //       'type' => 'D',
      //       'duration' => 4,
      //       'status' => 2
      //    ]);
    }
}
