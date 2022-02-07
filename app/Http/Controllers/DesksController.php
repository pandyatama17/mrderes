<?php

namespace App\Http\Controllers;

use App\Models\Desk;
use App\Models\Ticket;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DesksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Desks  $desks
     * @return \Illuminate\Http\Response
     */
    public function show(Desk $desks)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Desks  $desks
     * @return \Illuminate\Http\Response
     */
    public function edit(Desk $desks)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Desks  $desks
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,  $desks)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Desks  $desks
     * @return \Illuminate\Http\Response
     */
    public function destroy(Desk $desks)
    {
        //
    }

    public function getDesksByTicket($ticket_id)
    {
        $ticket = Ticket::find($ticket_id);
        if($ticket->type == 'D') $ticket->zone = 1;
        elseif($ticket->type == 'MR') $ticket->zone = 2;
        $start_time = Carbon::parse($ticket->datetime);
        $end_time = Carbon::parse($ticket->datetime)->addHours($ticket->duration);
        
        $reservedDesks = Reservation::select('desk_id')->whereRaw('(datetime_start >= "'.$start_time.'" and datetime_start <= "'.$end_time.'") or (datetime_end >= "'.$start_time.'" and datetime_end <= "'.$end_time.'")')->get();
        
        $desks = Desk::where('zone_id',$ticket->zone)->get();
        $desks->each(function($desk){
            $desk->status = '1'; 
        });

        foreach ($reservedDesks as $reservedDesk) 
        {
             foreach ($desks as $desk) {
                 if ($desk->id == $reservedDesk->desk_id) {
                        $desk->status = '0';
                }
            }
         }

        if ($ticket->zone == 1) {
             // echo json_encode($desks);
            return view('layouts.hotdesk')->with('desks',$desks)
                    ->with('start_time',Carbon::parse($start_time))
                    ->with('end_time',Carbon::parse($end_time));
        }
        else if ($ticket->zone == 2) {
            return view('layouts.meetingroom')->with('rooms',$desks)
                    ->with('start_time',Carbon::parse($start_time))
                    ->with('end_time',Carbon::parse($end_time));
        }
    }
}
