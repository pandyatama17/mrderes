<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $division = \App\Models\Division::create([
        //     'name' => 'Legal',
        //     'approver_id' => 21,
        // ]);
        // $division = \App\Models\Division::create([
        //     'name' => 'POGS',
        //     'approver_id' => 21,
        // ]);
        // $division = \App\Models\Division::create([
        //     'name' => 'C',
        //     'approver_id' => 0,
        // ]);
        // $user = \App\Models\User::create([
        //     'id' => 0,
        //     'division_id' => 0,
        //     'division_id' => 1, 'nik' => 000000,
        //     'name' => "RECP",
        //     'role' => 'Admin',
        //     'email' => 'resadmin@doku.com',
        //     'password' => Hash::make('admin12#'),
        //     'phone' => '081234567890',
        // ]);
        // $priv1 = \App\Models\ReservationPrivilege::create([
        //     'user_id' => $user->id,
        //     'zone_id' => 1,
        // ]);
        // $priv2 = \App\Models\ReservationPrivilege::create([
        //     'user_id' => $user->id,
        //     'zone_id' => 2,
        // ]);
        $users = array(
            // Hafiz Roky Rnoor
            // [ 'division_id' => 1, 'nik' => '1000017', 'name' => 'Hafiz', 'email' => 'hafiz@doku.com', 'role' => 'Head Officer', 'approver_id' => null, 'cc_id' => null, 'phone' => '81234567890', 'hot_desk' => true, 'meeting_room' => true, ],
            // [ 'division_id' => 1, 'nik' => '1000018', 'name' => 'Roky', 'email' => 'roky@doku.com', 'role' => 'Head Officer', 'approver_id' => null, 'cc_id' => null, 'phone' => '81234567890', 'hot_desk' => true, 'meeting_room' => true, ],
            // [ 'division_id' => 1, 'nik' => '1000019', 'name' => 'Rnoor', 'email' => 'rnoor@doku.com', 'role' => 'Head Officer', 'approver_id' => null, 'cc_id' => null, 'phone' => '81234567890', 'hot_desk' => true, 'meeting_room' => true, ],
            //TL DEV 1, TL DEV 2, REISA, TL SUPP1, TL SUPP2
            // [ 'division_id' => 1, 'nik' => '1000005', 'name' => 'TL DEV 1', 'email' => 'TLDEV1@doku.com', 'role' => 'TL', 'approver_id' => 2, 'cc_id' => null, 'phone' => '81234567890', 'hot_desk' => true, 'meeting_room' => true, ], [ 'division_id' => 1, 'nik' => '1000006', 'name' => 'TL DEV 2', 'email' => 'TLDEV2@doku.com', 'role' => 'TL', 'approver_id' => 2, 'cc_id' => null, 'phone' => '81234567890', 'hot_desk' => true, 'meeting_room' => true, ], [ 'division_id' => 1, 'nik' => '1000019', 'name' => 'Rnoor', 'role' => 'TL', 'approver_id' => 2, 'cc_id' => null, 'phone' => '81234567890', 'hot_desk' => true, 'meeting_room' => true, ], 
            // [ 'division_id' => 1, 'nik' => '1000010', 'email' => 'reisa@doku.com', 'name' => 'REISA', 'role' => 'TL', 'approver_id' => 3, 'cc_id' => null, 'phone' => '81234567890', 'hot_desk' => true, 'meeting_room' => true, ], [ 'division_id' => 1, 'nik' => '1000019', 'name' => 'Rnoor', 'role' => 'TL', 'approver_id' => 2, 'cc_id' => null, 'phone' => '81234567890', 'hot_desk' => true, 'meeting_room' => true, ], 
            // [ 'division_id' => 1, 'nik' => '1000015', 'name' => 'TL SUPP 1', 'email' => 'TLSUPP1@doku.com', 'role' => 'TL', 'approver_id' => 4, 'cc_id' => null, 'phone' => '81234567890', 'hot_desk' => true, 'meeting_room' => true, ], [ 'division_id' => 1, 'nik' => '1000019', 'name' => 'Rnoor', 'role' => 'TL', 'approver_id' => 2, 'cc_id' => null, 'phone' => '81234567890', 'hot_desk' => true, 'meeting_room' => true, ], 
            // [ 'division_id' => 1, 'nik' => '1000016', 'name' => 'TL SUPP 2', 'email' => 'TLSUPP2@doku.com', 'role' => 'TL', 'approver_id' => 4, 'cc_id' => null, 'phone' => '81234567890', 'hot_desk' => true, 'meeting_room' => true, ], [ 'division_id' => 1, 'nik' => '1000019', 'name' => 'Rnoor', 'role' => 'TL', 'approver_id' => 2, 'cc_id' => null, 'phone' => '81234567890', 'hot_desk' => true, 'meeting_room' => true, ],
            //mamah nakal
            //  [ 'division_id' => 5, 'nik' => '1000032', 'name' => 'CTK', 'email' => 'ctk@doku.com', 'role' => 'TL', 'approver_id' => null, 'cc_id' => null, 'phone' => '81234567890', 'hot_desk' => true, 'meeting_room' => true, ], 
            //  [ 'division_id' => 5, 'nik' => '1000028', 'name' => 'NOVA', 'email' => 'nova@doku.com', 'role' => 'TL', 'approver_id' => 21, 'cc_id' => null, 'phone' => '81234567890', 'hot_desk' => true, 'meeting_room' => true, ], 
            //  [ 'division_id' => 5, 'nik' => '1000029', 'name' => 'PALTI', 'email' => 'palti@doku.com', 'role' => 'TL', 'approver_id' => 22, 'cc_id' => 21, 'phone' => '81234567890', 'hot_desk' => true, 'meeting_room' => true, ], 
            //  [ 'division_id' => 5, 'nik' => '1000030', 'name' => 'TONO', 'email' => 'tono@doku.com', 'role' => 'TL', 'approver_id' => 22, 'cc_id' => 21, 'phone' => '81234567890', 'hot_desk' => true, 'meeting_room' => true, ], 
            //  [ 'division_id' => 5, 'nik' => '1000030', 'name' => 'RASNO', 'email' => 'rasno@doku.com', 'role' => 'TL', 'approver_id' => 23, 'cc_id' => 22, 'phone' => '81234567890', 'hot_desk' => true, 'meeting_room' => true, ], 
            //  [ 'division_id' => 5, 'nik' => '1000033', 'name' => 'GITHA', 'email' => 'githa@doku.com', 'role' => 'TL', 'approver_id' => 21, 'cc_id' => null, 'phone' => '81234567890', 'hot_desk' => true, 'meeting_room' => true, ], 
            //  [ 'division_id' => 5, 'nik' => '1000034', 'name' => 'YUAN', 'email' => 'yuan@doku.com', 'role' => 'TL', 'approver_id' => 27, 'cc_id' => 21, 'phone' => '81234567890', 'hot_desk' => false, 'meeting_room' => true, ], 
            
        );
        //dev  1-4
        // for ($i=1; $i <= 4; $i++) { 
        //     array_push($users,[ 'division_id' => 1, 'nik' => '100000'.$i, 'name' => 'DEV '.$i, 'email' => 'DEV'.$i.'@doku.com', 'role' => 'STAFF', 'approver_id' => ($i <= 2 ? 5 : 6), 'cc_id' => 2, 'phone' => '81234567890', 'hot_desk' => true, 'meeting_room' => true, ]); 
        // }
        //infra 1-3
        // for ($i=1; $i <= 3; $i++) { 
        //     array_push($users,[ 'division_id' => 1, 'nik' => '100000'.$i+6, 'name' => 'INFRA '.$i, 'email' => 'INFRA'.$i.'@doku.com', 'role' => 'STAFF', 'approver_id' =>7, 'cc_id' => 3, 'phone' => '81234567890', 'hot_desk' => true, 'meeting_room' => true, ]); 
        // }
        //supp  1-4
        // for ($i=1; $i <= 4; $i++) { 
        //     array_push($users,[ 'division_id' => 1, 'nik' => '100000'.$i+10, 'name' => 'SUPP '.$i, 'email' => 'SUPP '.$i.'@doku.com', 'role' => 'STAFF', 'approver_id' => ($i <= 2 ? 5 : 6), 'cc_id' => 2, 'phone' => '81234567890', 'hot_desk' => true, 'meeting_room' => true, ]); 
        // }

        foreach ($users as $u) {
            $insert = \App\Models\User::create([
                'division_id' => $u['division_id'],
                'nik' => $u['nik'],
                'name' => $u['name'],
                'email' => $u['email'],
                'role' => $u['role'],
                'approver_id' => $u['approver_id'],
                'cc_id' => $u['cc_id'],
                'phone' => $u['phone'],
                'password' => Hash::make('Doku12#'),
            ]);
            if ($u['hot_desk']) {
                $priv = \App\Models\ReservationPrivilege::create([
                    'user_id' => $insert->id,
                    'zone_id' => 1,
                ]);
            }
            if ($u['meeting_room']) {
                $priv = \App\Models\ReservationPrivilege::create([
                    'user_id' => $insert->id,
                    'zone_id' => 2,
                ]);
            }
        }

        // for ($i=11; $i <=18 ; $i++) { 
        //     $update = \App\Models\User::find($i);
        //     $update->id = $i-2;
        //     $update->save();
        // }
    }
}
