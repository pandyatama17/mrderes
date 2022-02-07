<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function accountSettings()
    {
        $crumbs = array('page'=>'Account Settings','pages'=>[['title'=>'User Area','url' => '#'],['title'=>Auth::user()->name,'url' => '#']]);
        return view('account.settings')->with('crumbs',$crumbs);
    }
    public function changePassword(Request $r)
    {
       $user = User::find(Auth::user()->id);

       $passwordMatch = Hash::check($r->oldpswd, $user->password);

       if ($passwordMatch) {
           if ($r->newpswd == $r->repswd) {
                $user->password = Hash::make($r->newpswd);
                try {
                    $user->save();
                    $message = ['type'=> 'success', 'body' => 'your password has been updated!','title'=> 'Changed password successfully!','redirect' => route('account_settings')];        

                } catch (\Throwable $th) {
                    $message = ['type'=> 'error', 'body' => $th->getMessage(),'title'=> 'Change password failed!','redirect' => '#'];        
                }
           }
           else
           {
                $message = ['type'=> 'error', 'body' => 'Password confirmation does not match! please check again','title'=> 'Change Password failed!','redirect' => '#'];        
           }
       }
       else
       {
            $message = ['type'=> 'error', 'body' => 'Old Password does not match our record!','title'=> 'Change password failed!','redirect' => '#'];        
       }
       echo json_encode($message);
    }
    public function update(Request $r)
    {
        $user = User::find(Auth::user()->id);
        $user->phone = $r->phone;
        
        try {
            $user->save();
            $message = ['type'=> 'success', 'body' => 'your information has been updated!','title'=> 'Information changed successfully!','redirect' => route('account_settings')];        

        } catch (\Throwable $th) {
            $message = ['type'=> 'error', 'body' => $th->getMessage(),'title'=> 'update failed!','redirect' => '#'];        
        }
        echo json_encode($message);
    }

    public function showTeam()
    {
        $user = User::find(Auth::user()->id);
        $team = User::where('approver_id',$user->id)->orWhere('cc_id',$user->id)->get();
        
        $crumbs = array('page'=>'Team','pages'=>[['title'=>'Approver Area','url' => '#'],['title'=>Auth::user()->name,'url' => '#']]);
        return view('approver.team')->with('crumbs',$crumbs)->with('team', $team);
    }
}
