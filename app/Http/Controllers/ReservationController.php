<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\Zone;
use App\Models\Desk;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Mail;
use \App\Mail\TicketRequestMailer;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class ReservationController extends Controller
{
    public function __construct(){
        // $now = Carbon::parse('2022-02-03 07:00:00')->addMinutes(15);
        $now = Carbon::now()->addMinutes(15);
        Reservation::whereNull('start_time')->where('datetime_start', '<=', $now)->delete();
    }
    public function index()
    {
        if (Auth::user() && Auth::user()->role == 'ADMIN') {
            return redirect()->route('calendar');
        }
        else
        {
            if (Auth::user()->email_verified_at) {
                return redirect()->route('calendar');
            }
            else
            {
                return redirect()->route('account_settings');
            }
        }
    }

    public function booking()
    {
        $crumbs = array('page'=>'Booking','pages'=>[['title'=>'Reservation','url' => '#']]);
        $zones = Zone::all();
        $desks = Desk::where('zone_id',1)->where('availability',1)->get();
        return view('reservation.index')->with('crumbs',$crumbs)->with('desks',$desks)->with('zones',$zones);
        
    }
    
    public function list()
    {
        $crumbs = array('page'=>'Hot Desks','pages'=>[['title'=>'Reservation','url' => '#'],['title'=>'Calendar','url' => '#']]);
        $zones = Zone::all();
        $desks = Desk::where('zone_id',1)->where('availability',true)->get();
        return view('reservation.list')->with('crumbs',$crumbs)->with('desks',$desks)->with('zones',$zones);
    }

    
    public function listMR()
    {
        $crumbs = array('page'=>'Meeting Room','pages'=>[['title'=>'Reservation','url' => '#'],['title'=>'Calendar','url' => '#']]);
        $zones = Zone::all();
        $desks = Desk::where('zone_id',2)->where('availability',true)->get();
        return view('reservation.meetingroom.list')->with('crumbs',$crumbs)->with('desks',$desks)->with('zones',$zones);
    }

    public function ticket()
    {
        $crumbs = array('page'=>'Ticket','pages'=>[['title'=>'Reservation','url' => '#']]);
        $tickets = Ticket::where('user_id',Auth::user()->id)->get();
        return view('reservation.ticket')->with('crumbs',$crumbs)->with('tickets',$tickets);
    }
    
    public function storeTicket(Request $r)
    {
        $datetime = Carbon::parse($r->request_date.' '.$r->request_time);
        // return $r;
        // return $datetime->format('l, d F, Y H:i');

        
        $ticket = new Ticket;
        $ticket->user_id = Auth::user()->id;
        $ticket->datetime = $datetime;
        $ticket->duration = $r->duration;
        $ticket->type = $r->type;

        if(!Auth::user()->approver_id)
        {
            $ticket->status = 1;
        }

        try {
            $ticket->save();
            if(Auth::user()->approver_id)
            {
                $mailTo = User::find(Auth::user()->approver_id)->email;
                $mailCC = (Auth::user()->cc_id ? User::find(Auth::user()->cc_id)->email : null );
                $mailVariables = [
                    'requester_name' => Auth::user()->name,
                    'request_date' => $datetime->format('l, d F, Y H:i').' - '.$datetime->addHours($r->duration)->format('l, d F, Y H:i'),
                    'type' => ($r->type == 'MR' ? 'Meeting Room' : 'Hot Desk'),
                    'request_notes' => $r->request_notes,
                    'ticket_url' => [
                        'accept' =>route('approve_ticket',$ticket->id),
                        'reject' => route('reject_ticket',$ticket->id),
                    ]
                ];
                $mail = Mail::to($mailTo);
                if (Auth::user()->cc_id) {
                    $mail->cc($mailCC);
                }
                $mail->bcc('bookingsystem@firoshal.com','Booking System');
                
                $mail->send(new TicketRequestMailer($mailVariables));
                $message = ['type'=> 'success', 'body' => 'Ticket sucsesfully requested','title'=>'Success'];
                // try {
                //     $mail->send(new TicketRequestMailer($mailVariables));
                //     $message = ['type'=> 'success', 'body' => 'Ticket sucsesfully requested','title'=>'Success'];
                // } catch (\Throwable $th) {
                //     $message = ['type'=> 'warning', 'body' => 'an error occured! please notify your approver to accept your request manually through the web application','title'=>'Oops..'];
                // }
            }
            else{
                $message = ['type'=> 'success', 'body' => 'Ticket sucsesfully created','title'=>'Success'];
            }
        } catch (\Throwable $th) {
            $message = ['type'=> 'error', 'body' => 'Ticket request failed, error '.$th->getMessage(),'title'=>'Failed'];
        }

        return redirect()->route('ticket')->with('message',$message);
    }
    public function approveTicket($ticket_id)
    {
        $ticket = Ticket::find($ticket_id);

        if ($ticket) {
            $ticket->status = 1;

            $ticket->save();
            $message = ['type'=> 'success', 'body' => 'Ticket approved','title'=>'Success'];
        }
        else{
            $message = ['type'=> 'error', 'body' => 'Ticket unavailable or has been removed!','title'=>'error'];
        }

        return redirect()->route('ticket')->with('message',$message);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $r)
    {
        // return $r;
        $ticket = Ticket::find($r->ticket_id);

        $reserv = new Reservation;
        $reserv->desk_id = $r->desk_id;
        $reserv->user_id = Auth::user()->id;

        if (Auth::user()->approver_id) {
            $reserv->supervisor_id = User::find(Auth::user()->approver_id)->id;
        }
        else{
            $reserv->supervisor_id = Auth::user()->id;
        }
        $reserv->datetime_start = Carbon::parse($ticket->datetime);
        $reserv->datetime_end = Carbon::parse($ticket->datetime)->addHours($ticket->duration);
        $reserv->duration = $ticket->duration;
        $reserv->approved = 1;
        $reserv->created_at = Carbon::now();
        if ($ticket->type == 'D' ?  $type = 'Desk' : $type = 'Room');

        try {
            $reserv->save();
            $ticket->status = 2;
            $ticket->save();
            $message = ['type'=> 'success', 'body' => $type.' sucsesfully reserved for '.Carbon::parse($ticket->datetime)->format('l, d F, Y'),'title'=>'Success'];
        } catch (\Throwable $th) {
            $message = ['type'=> 'error', 'body' => $type.' reservation failed, error '.$th->getMessage(),'title'=>'Failed'];        
        }
        return redirect()->route('calendar')->with('message',$message);
    }

    public function getDesksWithDate()
    {
        $crumbs = array('page'=>'Calendar','pages'=>[['title'=>'Reservation','url' => '#']]);
        $zones = Zone::all();
        $desks = Desk::where('zone_id',1)->get();
        return view('reservation.list')->with('crumbs',$crumbs)->with('desks',$desks)->with('zones',$zones);
    }

    public function getCalendarByDate($timestamp)
    {
        $dt = Carbon::createFromTimestampMs($timestamp);
        $zones = Zone::all();
        $desks = Desk::where('zone_id',1)->where('availability',true)->get();
        return view('reservation.calendar')->with('dtSelected',$dt)->with('desks',$desks)->with('zones',$zones);
    }

    public function getMRCalendarByDate($timestamp)
    {
        $dt = Carbon::createFromTimestampMs($timestamp);
        $zones = Zone::all();
        $desks = Desk::where('zone_id',2)->where('availability',true)->get();
        return view('reservation.meetingroom.calendar')->with('dtSelected',$dt)->with('desks',$desks)->with('zones',$zones);
    }

    public function showRerservations()
    {
        $req = Reservation::where('user_id',Auth::user()->id)->get();
        $crumbs = array('page'=>'My Reservations','pages'=>[['title'=>'Reservation','url' => '#']]);
        
        return view('reservation.mine')->with('crumbs',$crumbs)->with('reservations',$req);
    }

    public function checkIn($reservation_id)
    {
        $reservation = Reservation::find($reservation_id);
        if (Auth::user()->id == $reservation->user_id || Auth::user()->role == 'ADMIN') 
        {
            $reservation->start_time = Carbon::now(); 
            try {
                $reservation->save();
                $message = ['type'=> 'success', 'body' => 'for reservation #'.$reservation_id ,'title'=>'Checked In sucsesfully!','redirect' => route('calendar_mr')];
            } catch (\Throwable $th) {
                $message = ['type'=> 'error', 'body' => $th->getMessage(),'title'=> 'Check In failed!','redirect' => '#'];        
            }
        }
        
        echo json_encode($message);
    }

    public function deleteReservation($reservation_id)
    {
        $res = Reservation::find($reservation_id);
        $deletedRes = $res;
        $desk = Desk::find($res->desk_id);
        try {
            if (Auth::user()->role == 'ADMIN' || Auth::user()->id == $res->user_id) {
                $res->delete();
                ($desk->zone_id == 1 ? $redir = 'calendar' : $redir = 'calendar_mr');
                $message = ['type'=> 'success', 'body' => 'for '.$desk->desk_name.' at '.Carbon::parse($deletedRes->datetime_start)->format('l, d F, Y H:i') ,'title'=>'Reservation Succesfully Canceled!','redirect' => route($redir)];
            }
            else
            {
                $message = ['type'=> 'error', 'body' => 'unauthorized!' ,'title'=>'Failed to Cancel Reservation!','redirect' => '#'];
            }
        } catch (\Throwable $th) {
            $message = ['type'=> 'error', 'body' => $th->getMessage() ,'title'=>'Failed to Cancel Reservation!','redirect' => '#']; 
        }
        echo json_encode($message);
    }

    public function reservationsAjax($type)
    {
        switch ($type) {
            case 1:
            case 5:
                $res = Reservation::where('user_id',Auth::user()->id)->get();
                break;
            case 2:
                $res = Reservation::where('user_id',Auth::user()->id)->whereNull('start_time')->get();
                break;
            case 3:
                $res = Reservation::where('user_id',Auth::user()->id)->whereNotNull('start_time')->get();
                break;
            case 4:
                $res = Reservation::where('user_id',Auth::user()->id)->whereNotNull('start_time')->get();
                break;
            case 6: 
                $res = Reservation::select('reservations.*')
                        ->leftJoin('desks','desks.id','=','reservations.desk_id')
                        ->where('desks.zone_id','=',1)
                        ->where('reservations.user_id',Auth::user()->id)
                        ->get();
                break;
            case 7: 
                $res = Reservation::select('reservations.*')
                        ->leftJoin('desks','desks.id','=','reservations.desk_id')
                        ->where('desks.zone_id','=',2)
                        ->where('reservations.user_id',Auth::user()->id)
                        ->get();
                break;
            default:
                $res = Reservation::where('user_id',Auth::user()->id)->get();
                break;
        }
        // return $res;
        return view('tables.reservations')->with('reservations',$res);
    }

    public function showTicketRequests()
    {
        $crumbs = array('page'=>'Tickets','pages'=>[['title'=>'Reservation','url' => '#']]);
        
        if (Auth::user()->role != "STAFF") {
            if (Auth::user()->role == "ADMIN") {
                $tickets = Ticket::select('tickets.*','approver.id as approver','users.name as user_name')
                    ->leftJoin('users','users.id','=','tickets.user_id')
                    ->leftJoin('users as approver','approver.id','=','users.approver_id')
                    // ->where('tickets.status',0)
                    ->get();
            }
            else
            {
                $tickets = Ticket::select('tickets.*','approver.id as approver','users.name as user_name')
                    ->leftJoin('users','users.id','=','tickets.user_id')
                    ->leftJoin('users as approver','approver.id','=','users.approver_id')
                    // ->where('tickets.status',0)
                    ->where('approver.id',Auth::user()->id)
                    ->get();
            }
        }
        else
        {
            Session::flash('message', ['type'=> 'error', 'body' => 'You are not an Approver! ','title'=>'Unauthorized!']);
            return redirect()->back();
        }
        
        // return $tickets;
        return view('reservation.tickets')->with('tickets',$tickets)->with('crumbs',$crumbs);
    }
    public function getTickets($method)
    {
        if ($method < 4) {
            if (Auth::user()->role != "STAFF") {
                if (Auth::user()->role == "ADMIN") {
                    $tickets = Ticket::select('tickets.*','approver.id as approver','users.name as user_name')
                        ->leftJoin('users','users.id','=','tickets.user_id')
                        ->leftJoin('users as approver','approver.id','=','users.approver_id')
                        ->where('tickets.status',$method)
                        ->get();
                }
                else
                {
                    $tickets = Ticket::select('tickets.*','approver.id as approver','users.name as user_name')
                        ->leftJoin('users','users.id','=','tickets.user_id')
                        ->leftJoin('users as approver','approver.id','=','users.approver_id')
                        ->where('tickets.status',$method)
                        ->where('approver.id',Auth::user()->id)
                        ->get();
                }
            }
            else
            {
                Session::flash('message', ['type'=> 'error', 'body' => 'You are not an Approver! ','title'=>'Unauthorized!']);
                return redirect()->back();
            }
        }
        else
        {
            if ($method > 3 && $method < 6) {
                switch ($method) {
                    case 4:
                        $dType = 'D';
                        break;
                    case 5:
                        $dType = 'MR';
                        break;
                    default:
                        # code...
                        break;
                }
                if (Auth::user()->role != "STAFF") {
                    if (Auth::user()->role == "ADMIN") {
                        $tickets = Ticket::select('tickets.*','approver.id as approver','users.name as user_name')
                            ->leftJoin('users','users.id','=','tickets.user_id')
                            ->leftJoin('users as approver','approver.id','=','users.approver_id')
                            ->where('tickets.type',$dType)
                            ->get();
                    }
                    else
                    {
                        $tickets = Ticket::select('tickets.*','approver.id as approver','users.name as user_name')
                            ->leftJoin('users','users.id','=','tickets.user_id')
                            ->leftJoin('users as approver','approver.id','=','users.approver_id')
                            ->where('tickets.type',$dType)
                            ->where('approver.id',Auth::user()->id)
                            ->get();
                    }
                }
                else
                {
                    Session::flash('message', ['type'=> 'error', 'body' => 'You are not an Approver! ','title'=>'Unauthorized!']);
                    return redirect()->back();
                }
            }
            else
            {
                if (Auth::user()->role != "STAFF") {
                    if (Auth::user()->role == "ADMIN") {
                        $tickets = Ticket::select('tickets.*','approver.id as approver','users.name as user_name')
                            ->leftJoin('users','users.id','=','tickets.user_id')
                            ->leftJoin('users as approver','approver.id','=','users.approver_id')
                            ->get();
                    }
                    else
                    {
                        $tickets = Ticket::select('tickets.*','approver.id as approver','users.name as user_name')
                            ->leftJoin('users','users.id','=','tickets.user_id')
                            ->leftJoin('users as approver','approver.id','=','users.approver_id')
                            ->where('approver.id',Auth::user()->id)
                            ->get();
                    }
                }
                else
                {
                    Session::flash('message', ['type'=> 'error', 'body' => 'You are not an Approver! ','title'=>'Unauthorized!']);
                    return redirect()->back();
                }
            }
        }

        return view('tables.tickets')->with('tickets',$tickets);
    }

    public function rejectTicket($ticket_id)
    {
        $ticket = Ticket::find($ticket_id);

        if ($ticket) {
            $ticket->status = 3;

            $ticket->save();
            $message = ['type'=> 'success', 'body' => 'Ticket rejected','title'=>'Success'];
        }
        else{
            $message = ['type'=> 'error', 'body' => 'Ticket unavailable or has been removed!','title'=>'error'];
        }

        return redirect()->route('ticket')->with('message',$message);
    }
}