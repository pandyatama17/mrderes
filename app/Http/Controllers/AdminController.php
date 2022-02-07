<?php

namespace App\Http\Controllers;

use App\Models\Desk;
use App\Models\ReservationPrivilege;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    protected $role;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role != 'ADMIN') {
                Session::flash('message', ['type'=> 'error', 'body' => 'You are not an Administrator! ','title'=>'Unauthorized!']);
                return redirect()->back();
            }
            else
            {
                return $next($request);
            }
        });
    }

    public function showUsers()
    {
       $users = User::leftJoin('users as approver',function($join){
                        $join->on('approver.id','=','users.approver_id');
                    })->leftJoin('users as cc',function($join){
                        $join->on('cc.id','=','users.cc_id');
                    })->select('users.*','approver.name as approver','cc.name as cc')
                    ->get();
       $crumbs = array('page'=>'Users','pages'=>[['title'=>'Admin Area','url' => '#']]);
       return view('admin.users')->with('crumbs',$crumbs)->with('users',$users);

    }

    public function storeUser(Request $r)
    {
        // dd($r->cc);
        if ($r->user_id) {
            $user = User::find($r->user_id);
            $action = "Update";
        }
        else
        {
            $user = new User;
            $action = "Submit";
        }

        $user->nik = $r->nik;
        $user->name = $r->name;
        $user->email = $r->email;
        $user->division_id = $r->division_id;
        $user->role = $r->role;
        $user->approver_id = ($r->approver ? $r->approver : null);
        $user->cc_id = ($r->cc ? $r->cc : null);
        $user->phone = $r->phone;
        $user->password = Hash::make('Doku12#');
        $user->created_at = Carbon::now();

        try {
            $stored = $user->save();
            if ($stored) {
                try {
                    if ($action == 'Update') {
                        $oldPriv = ReservationPrivilege::where('user_id',$user->id)->delete();
                    }
                    foreach ($r->zones as $zone) {
                        $rp = new ReservationPrivilege;
                        $rp->user_id = $user->id;
                        $rp->zone_id = $zone;
                        $rp->save();
                    }
                    $message = ['type'=> 'success', 'body' => 'User '.$action.' success!','title'=>'Success'];
                } catch (\Throwable $th) {
                    $message = ['type'=> 'error', 'body' => 'User '.$action.' Failed, error '.$th->getMessage(),'title'=>'Failed'];
                }
            }
            else{
                $message = ['type'=> 'error', 'body' => 'User '.$action.' Failed, error '.$th->getMessage(),'title'=>'Failed'];
            }
        } catch (\Throwable $th) {
            $message = ['type'=> 'error', 'body' => 'User '.$action.' failed, error '.$th->getMessage(),'title'=>'Failed'];
        }
        return redirect()->route('show_users')->with('message',$message);
        
    }
    public function showDesks()
    {
       $crumbs = array('page'=>'Desks','pages'=>[['title'=>'Admin Area','url' => '#']]);
       $desks = Desk::leftJoin('zones', 'zones.id','=','desks.zone_id')
                    ->leftJoin('desks as parent', 'parent.id','=','desks.parent_id')
                    ->select('desks.*','zones.name as zone', 'parent.desk_name as parent')
                    ->get();
       return view('admin.desks')->with('crumbs',$crumbs)->with('desks',$desks);
    //    return $desks;
    }
    public function createReservation()
    {
        $crumbs = array('page'=>'Create Reservation','pages'=>[['title'=>'Admin Area','url' => '#'],['title'=>'Reservation','url' => route('calendar')]]);
        $users = User::where('id','>',1)->get();
        return view('admin.reservation.form')->with('crumbs',$crumbs)->with('users',$users);
    }

    public function getDesks(Request $r)
    {
        // return $r;
        $start_time = Carbon::parse($r->date.' '.$r->time);
        $end_time = Carbon::parse($start_time)->addHours($r->duration);
        
        $reservedDesks = Reservation::select('desk_id')->whereRaw('(datetime_start >= "'.$start_time.'" and datetime_start <= "'.$end_time.'") or (datetime_end >= "'.$start_time.'" and datetime_end <= "'.$end_time.'")')->get();
        
        $desks = Desk::where('zone_id',$r->type)->get();
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

        if ($r->type == 1) {
             // echo json_encode($desks);
            return view('layouts.hotdesk')->with('desks',$desks)
                    ->with('start_time',Carbon::parse($start_time))
                    ->with('end_time',Carbon::parse($end_time));
        }
        else if ($r->type == 2) {
            return view('layouts.meetingroom')->with('rooms',$desks)
                    ->with('start_time',Carbon::parse($start_time))
                    ->with('end_time',Carbon::parse($end_time));
        }
    }

    public function storeReservation(Request $r)
    {
        // return json_encode(['type'=> 'success', 'body' => $r->user_id,'title'=>'Success']);
        $ticket = new Ticket;
        $reserv = new Reservation;

        $user = User::find($r->user_id);
        $datetime = Carbon::parse($r->date.' '.$r->time);
        if ($r->type == 1 ?  $type = 'D' : $type = 'MR');

        $ticket->user_id = $user->id;
        $ticket->datetime = $datetime;
        $ticket->duration = $r->duration;
        $ticket->type = $type;
        $ticket->status = 2;

        try {
            $ticket->save();
            $reserv = new Reservation;
            $reserv->desk_id = $r->desk_id;
            $reserv->user_id = $user->id;

            
            $reserv->supervisor_id = Auth::user()->id;

            $reserv->admin_id = Auth::user()->id;
            $reserv->datetime_start = Carbon::parse($datetime);
            $reserv->datetime_end = Carbon::parse($datetime)->addHours($r->duration);
            $reserv->duration = $r->duration;
            $reserv->approved = 1;
            $reserv->start_time = Carbon::now();
            
            if ($type == 'D' ?  $type = 'Desk' : $type = 'Room');
            if($user->role == 'ADMIN' ? $receipt = $r->user_name : $receipt = $user->name);
            
            try {
                $reserv->save();
                $message = ['type'=> 'success', 'body' => $type.' reservation created sucsesfully for '.$receipt.' at '.Carbon::parse($ticket->datetime)->format('l, d F, Y'),'title'=>'Success','redirect' => route('calendar_mr')];
            } catch (\Throwable $th) {
                $message = ['type'=> 'error', 'body' => $th->getMessage(),'title'=> $type.' reservation failed','redirect' => '#'];        
            }


        } catch (\Throwable $th) {
            $message = ['type'=> 'error', 'body' => $th->getMessage(),'title'=>'Room Reservation Creation Failed','redirect' => '#'];
        }
        echo json_encode($message);
    }

    public function toggleUserStatus($user_id)
    {
        $user = User::find($user_id);

        if ($user->email_verified_at) 
        {
            $user->email_verified_at = null;
            $action = "Disabled";
        }
        else
        {
            $user->email_verified_at = Carbon::now();
            $action = "Enabled";
        }
        try {
            $user->save();
            $message = ['type'=> 'success', 'body' => 'User '.$user->name.' has been '.$action,'title'=> $action.' Successful!','redirect' => route('show_users')];
        } catch (\Throwable $th) {
            $message = ['type'=> 'success', 'body' => $th->getMessage(),'title'=>'Failed to '.$action.' User '.$user->name.'!','redirect' => route('show_users')];
        }
        echo json_encode($message);
    }

    public function getUsers($method)
    {
        switch ($method) {
            case 'STAFF':
                $users = User::leftJoin('users as approver',function($join){
                    $join->on('approver.id','=','users.approver_id');
                })->leftJoin('users as cc',function($join){
                    $join->on('cc.id','=','users.cc_id');
                })->where('users.role','STAFF')
                ->select('users.*','approver.name as approver','cc.name as cc')->get();
                break;
            case 'APPROVER':
                $users = User::leftJoin('users as approver',function($join){
                    $join->on('approver.id','=','users.approver_id');
                })->leftJoin('users as cc',function($join){
                    $join->on('cc.id','=','users.cc_id');
                })->where('users.role','TL')
                ->select('users.*','approver.name as approver','cc.name as cc')->get();
                break;
            case 'CC':
                $users = User::leftJoin('users as approver',function($join){
                    $join->on('approver.id','=','users.approver_id');
                })->leftJoin('users as cc',function($join){
                    $join->on('cc.id','=','users.cc_id');
                })->where('users.role','Head Officer')
                ->select('users.*','approver.name as approver','cc.name as cc')->get();
                break;
            case 'HD':
                $users = User::leftJoin('users as approver',function($join){
                    $join->on('approver.id','=','users.approver_id');
                })->leftJoin('users as cc',function($join){
                    $join->on('cc.id','=','users.cc_id');
                })->leftJoin('reservation_privileges as privilege',function($join){
                    $join->on('privilege.user_id','=','users.id');
                })->where('privilege.zone_id',1)
                ->select('users.*','approver.name as approver','cc.name as cc')->get();
                break;
            case 'MR':
                $users = User::leftJoin('users as approver',function($join){
                    $join->on('approver.id','=','users.approver_id');
                })->leftJoin('users as cc',function($join){
                    $join->on('cc.id','=','users.cc_id');
                })->leftJoin('reservation_privileges as privilege',function($join){
                    $join->on('privilege.user_id','=','users.id');
                })->where('privilege.zone_id',2)
                ->select('users.*','approver.name as approver','cc.name as cc')->get();
                break;
            default:
                $users = User::leftJoin('users as approver',function($join){
                    $join->on('approver.id','=','users.approver_id');
                })->leftJoin('users as cc',function($join){
                    $join->on('cc.id','=','users.cc_id');
                })
                ->select('users.*','approver.name as approver','cc.name as cc')->get();
               break;
       }
    //    echo json_encode($users);
       

       return view('admin.users_content')->with('users',$users);
    }
}
