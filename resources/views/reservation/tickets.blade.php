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
            <li class="active first"><a href="99" class="text-sub sortTickets"><i class="blue-text material-icons small-icons mr-2"> fiber_manual_record </i> All</a></li>  
            <li><a href="0" class="text-sub sortTickets"><i class="grey-text material-icons small-icons mr-2"> fiber_manual_record </i> Pending Approval</a></li>  
            <li><a href="1" class="text-sub sortTickets"><i class="green-text material-icons small-icons mr-2"> fiber_manual_record </i> Approved</a></li>
            <li><a href="2" class="text-sub sortTickets"><i class="yellow-text material-icons small-icons mr-2"> fiber_manual_record </i> Used</a></li>
            <li><a href="3" class="text-sub sortTickets"><i class="red-text material-icons small-icons mr-2"> fiber_manual_record </i> Rejected</a></li>
            <li class="sidebar-title">Type</li>
            <li><a href="99" class="text-sub sortTickets"><i class="material-icons small-icons mr-2"> event_seat </i> All</a></li>  
            <li><a href="4" class="text-sub sortTickets"><i class="material-icons small-icons mr-2"> table_restaurant </i> Hot Desk</a></li>  
            <li><a href="5" class="text-sub sortTickets"><i class="material-icons small-icons mr-2"> meeting_room </i> Meeting Room</a></li>
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
<div class="content-area content-right @if($agent->isMobile()) pt-5 mt-5 @endif">
    <div class="app-wrapper @if($agent->isMobile()) pt-5 mt-5 @endif" id="ticketsTable">
        <div class="datatable-search">
            <i class="material-icons mr-2 search-icon">search</i>
            <input type="text" placeholder="Search Ticket Date, time, or type" class="app-filter" id="global_filter">
        </div>
        <div id="button-trigger" class="card card card-default scrollspy border-radius-6 fixed-width">
            <div class="card-content p-0" id="myReservationsTable">
                <table id="data-table-contact" class="display">
                    <thead>
                        <tr>
                            <th class="all">Ticket Date</th>
                            <th class="all">Time</th>
                            <th class="all">Type</th>
                            <th>User</th>
                            <th>Status</th>
                            <th>Response Time</th>
                            <th class="all">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tickets as $ticket)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($ticket->datetime)->format('Y-m-d') }}</td>
                                <td>{{ \Carbon\Carbon::parse($ticket->datetime)->format('H:i') }} - {{ \Carbon\Carbon::parse($ticket->datetime)->addHours($ticket->duration)->format('H:i') }}</td>
                                <td>
                                    @switch($ticket->type)
                                        @case('MR')
                                            <span class="badge purple border-radius-6">Hot Desk</span>
                                            @break
                                        @case('D')
                                            <span class="badge amber border-radius-6">Meeting Room</span>
                                            @break
                                        @default                               
                                    @endswitch
                                </td>
                                <td>{{ $ticket->user_name }}</td>
                                <td>
                                    @switch($ticket->status)
                                        @case(0)
                                            <span class="badge grey border-radius-6">Pending Approval</span>
                                            @break
                                        @case(1)
                                            <span class="badge green border-radius-6">Approved</span>
                                            @break
                                        @case(2)
                                            <span class="badge amber border-radius-6">Used</span>
                                            @break
                                        @case(3)
                                            <span class="badge red border-radius-6">Rejected</span>
                                            @break
                                        @default                               
                                    @endswitch
                                </td>
                                <td>{{ $ticket->status > 0 ? $ticket->updated_at : "-"}}</td>
                                <td>
                                    @if ($ticket->status == 1 && Auth::user()->id == $ticket->user_id)
                                        <a href="{{ route('booking') }}" class="waves-effect green-text ">
                                            <i class="green-text material-icons left">event_seat</i> | Use Ticket
                                        </a>
                                    @endif
                                    @if ($ticket->status == 0 && (Auth::user()->id == $ticket->approver || Auth::user()->role == "ADMIN"))
                                        <a href="{{ route('approve_ticket',$ticket->id) }}" class="waves-effect green-text ">
                                            <i class="green-text material-icons left">thumb_up_off_alt</i> | Approve Ticket
                                        </a>
                                        <br>
                                        <a href="{{ route('reject_ticket',$ticket->id) }}" class="waves-effect red-text ">
                                            <i class="red-text material-icons left">thumb_down_off_alt</i> | Reject Ticket
                                        </a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
       
@endsection