@extends('layouts.pagewrapper')
@section('page')
<link rel="stylesheet" href="{{ asset('app-assets/css/pages/app-sidebar.css') }}">
<link rel="stylesheet" href="{{ asset('app-assets/css/pages/app-contacts.css') }}">

<div class="sidebar-left sidebar-fixed left ">
  <div class="sidebar" style="margin-left:-10px">
    <div class="sidebar-content">
      
      <div id="sidebar-list" class="sidebar-menu list-group position-relative animate fadeLeft delay-1 pt-0" >
        <div class="sidebar-list-padding app-sidebar sidenav" id="contact-sidenav">
          <ul class="contact-list display-grid">
            <li class="sidebar-title white-text">Filters</li>
            <li class="active first"><a href="all" class="text-sub sortUsers" id="adminUserListTrigger"><i class="material-icons mr-2"> timeline </i> All Users</a></li>
            {{-- <li><a href="javascript:void(0)" class="text-sub sortUsers"><i class="material-icons mr-2"> pending_actions </i> Pending Requests</a></li>
            <li><a href="javascript:void(0)" class="text-sub sortUsers"><i class="material-icons mr-2"> event_available </i> Approved Requests</a></li>
            <li><a href="javascript:void(0)" class="text-sub sortUsers"><i class="material-icons mr-2"> history </i> History</a></li> --}}
            
            <li class="sidebar-title">ROLE</li>
            <li><a href="STAFF" class="text-sub sortUsers"><i class="material-icons mr-2"> badge </i>Staff</a></li>
            <li><a href="APPROVER" class="text-sub sortUsers"><i class="material-icons mr-2"> assignment_ind </i> Approver</a></li>
            <li><a href="CC" class="text-sub sortUsers"><i class="material-icons mr-2"> contact_mail </i> CC Approver</a></li>
            
            <li class="sidebar-title">Privileges</li>
            <li><a href="all" class="text-sub sortUsers"><i class="blue-text material-icons small-icons mr-2"> fiber_manual_record </i> All</a></li>  
            <li><a href="HD" class="text-sub sortUsers"><i class="purple-text material-icons small-icons mr-2"> fiber_manual_record </i> Hot Desks</a></li>  
            <li><a href="MR" class="text-sub sortUsers"><i class="yellow-text material-icons small-icons mr-2"> fiber_manual_record </i> Meeting Rooms</a></li>
            <li class="sidebar-title">Options</li>
            <li><a href="#" class="text-sub" id="adminAddUserTrigger"><i class="material-icons mr-2"> person_add </i> User Form</a></li>
        </ul>
        </div>
      </div>
      <a href="#" data-target="contact-sidenav" class="sidenav-trigger hide-on-large-only pt-5 mt-5"><i class="material-icons">menu</i> <span class="white-text"> Filters</span></a>
    </div>
  </div>
</div>
@if ($agent->isMobile())
<div class="clearfix pt-5 mt-4"></div>
@endif
<div class="content-area content-right @if($agent->isMobile()) pt-5 mt-5 @endif" id="content-list" style="height: 10%">
    <div class="app-wrapper @if($agent->isMobile()) pt-5 mt-5 @endif">
        <div class="datatable-search">
            <i class="material-icons mr-2 search-icon">search</i>
            <input type="text" placeholder="Search Users" class="app-filter" id="global_filter">
        </div>
        <div id="button-trigger" class="card card card-default scrollspy border-radius-6 fixed-width">
            <div class="card-content p-0">
                <table id="data-table-contact" class="display">
                    <thead>
                        <tr>
                            <th class="all">Name</th>
                            <th class="all">Email</th>
                            <th class="all">Phone</th>
                            <th class="none">Role</th>
                            <th class="none">User Status</th>
                            <th class="none">Privileges</th>
                            <th class="none">Approver</th>
                            <th class="none">CC Approver</th>
                            <th class="all">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>
                                    @if ($user->role == 'TL')
                                        Approver
                                    @elseif($user->role == 'Head Officer')
                                        CC Approver
                                    @else
                                        Staff
                                    @endif
                                </td>
                                <td>
                                    @if ($user->email_verified_at)
                                        <span class="badge green border-radius-6">Active</span>
                                    @else
                                        <span class="badge orange border-radius-6">Suspended</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $roles = \App\Models\ReservationPrivilege::where('user_id',$user->id)->get();
                                    @endphp
                                    @foreach ($roles as $role)
                                        @if ($role->zone_id == 1)
                                            <span class="badge purple border-radius-6">Hot Desk</span>
                                        @else
                                            <span class="badge amber border-radius-6">Meeting Room</span>
                                        @endif
                                    @endforeach
                                </td>
                                
                                <td>{{ $user->approver_id ? $user->approver : "-" }}</td>
                                <td>{{ $user->cc_id ? $user->cc : "-" }}</td>
                                <td>
                                    <a href="#" class="light-blue-text text-darken-4 left editUserTrigger"
                                        href="{{ $user->id }}"
                                        data-id= "{{ $user->id }}"
                                        data-approver_id= "{{ $user->approver_id }}"
                                        data-cc_id= "{{ $user->cc_id }}"
                                        data-division_id= "{{ $user->division_id }}"
                                        data-nik= "{{ $user->nik }}"
                                        data-name= "{{ $user->name }}"
                                        data-email= "{{ $user->email }}"
                                        data-role= "{{ $user->role }}"
                                        data-phone= "{{ $user->phone }}"
                                    >
                                        <i class="material-icons left light-blue-text text-darken-4">edit</i>Edit Information
                                    </a>
                                    <br>
                                    <a href="#" class="purple-text text-darken-4 left resetUserPasswordTrigger"
                                        data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}"
                                        > 
                                        <i class="material-icons left purple-text text-darken-4">password</i>Reset Password 
                                    </a>
                                    <a href="#" class="{{ ($user->email_verified_at ? "orange-text" : "green-text") }} text-darken-4 left toggleUserStatusTrigger"
                                        data-id="{{ $user->id }}"
                                        data-name="{{ $user->name }}"
                                    >
                                        @if ($user->email_verified_at)
                                            <i class="material-icons left orange-text text-darken-4">lock</i>Suspend User
                                        @else
                                            <i class="material-icons left green-text text-darken-4">lock_open</i>Enable User
                                        @endif
                                    </a>
                                </td>
                                {{-- <td>{{ $user->approver_id ? \App\Models\User::find($user->approver_id)->name : "-" }}</td>
                                <td>{{ $user->cc_id ? \App\Models\User::find($user->cc_id)->name : "-" }}</td> --}}
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
       <div class="clearfix"></div>
       <div class="content-area content-right @if($agent->isMobile()) pt-5 mt-5 @endif" id="content-form">
        <div class="app-wrapper @if($agent->isMobile()) pt-5 mt-5 @endif">
            <div id="button-trigger" class="card card card-default scrollspy border-radius-6 fixed-width">
                <div class="card-content p-5 pb-1">
                    <div class="row">
                        <form class="col s12" method="POST" action="{{ route('submit_user') }}" id="userForm">
                            @csrf
                            <input type="hidden" name="user_id" id="idInput">
                            <div class="row">
                                <div class="input-field col s6 m4 l3">
                                    <input id="nikInput" name="nik" type="number" class="validate">
                                    <label for="nikInput">NIK</label>
                                </div>
                                <div class="input-field col s6 m4 l5">
                                    <input id="nameInput" name="name" type="text" class="validate">
                                    <label for="nameInput">Name</label>
                                </div>
                                <div class="input-field col s6 m4 l4">
                                    <input id="emailInput" name="email" type="email" class="validate">
                                    <label for="emailInput">Email</label>
                                </div>
                                <div class="input-field col s6 m4 l5">
                                    <input id="phoneInput" name="phone" type="number" class="validate">
                                    <label for="phoneInput">Phone</label>
                                </div>
                            </div>
                            <div class="divider mb-2 mt-1"></div>
                            <div class="row">
                                <div class="input-field col s4">
                                <select name="division_id" id="division_idInput">
                                    <option selected disabled>Select Division...</option>
                                    @foreach (\App\Models\Division::all() as $div)
                                        <option value="{{ $div->id }}">{{ $div->name }}</option>
                                    @endforeach
                                </select>
                                <label for="division_idInput">Division</label>
                                </div>
                                <div class="input-field col s8">
                                    <select name="role" id="roleInput">
                                        <option selected disabled>Select Role...</option>
                                        <option value="Head Officer">Head Officer</option>
                                        <option value="TL">TL</option>
                                        <option value="STAFF">STAFF</option>
                                    </select>
                                    <label for="roleInput">Role</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-field col s6">
                                    <select name="approver" id="approverInput" class="select2 browser-default">
                                        <option selected value="">-</option>
                                        @foreach (\App\Models\User::where('id','>',1)->where('role','!=','STAFF')->get() as $usr)
                                            <option value="{{ $usr->id }}">{{ $usr->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="approverInput">Approver</label>
                                </div>
                                <div class="input-field col s6">
                                    <select name="cc" id="ccInput" class="select2 browser-default">
                                        <option selected value="">-</option>
                                        @foreach (\App\Models\User::where('id','>',1)->where('role','!=','STAFF')->get() as $usr)
                                            <option value="{{ $usr->id }}">{{ $usr->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="ccInput">CC Approver</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s6">
                                    <label for="">Access</label>
                                    <p>
                                    <label>
                                        <input type="checkbox" checked readonly name='zones[]' value="2"/>
                                        <span>Meeting Room</span>
                                    </label>
                                    </p>
                                    <p>
                                    <label>
                                        <input type="checkbox" name='zones[]' value="1"/>
                                        <span>Hot Desk</span>
                                    </label>
                                    </p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col s12 display-flex justify-content-end form-action">
                                    <button type="submit" class="btn indigo waves-effect waves-light mr-2">
                                      Submit
                                    </button>
                                </div>
                            </div>
                        </form>
                      </div>
                </div>
            </div>
        </div>
    </div>
@endsection