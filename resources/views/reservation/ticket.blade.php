@extends('layouts.pagewrapper')
@section('page')
@php
$demoTickets = (object) [['date' => \Carbon\Carbon::now(),'type'=>'M', 'status' =>0],['date' => \Carbon\Carbon::now()->addDays(2),'type'=>'D', 'status' => 1],['date' => \Carbon\Carbon::now()->addDays(5),'type'=>'M', 'status' =>2],]
@endphp
<div class="col s12 m12 l12">
<br>
<div class="card center">
    <div class="card-content">
        <p class="caption ">Request a ticket to book Hot Desks or Meeting Rooms</p>
      </div>
</div>
<div class="row">
    <div class="col s12 m6 l5">
        <ul class="collapsible @if (count(\App\Models\Ticket::where('status','<',2)->where('user_id',Auth::user()->id)->get()) > 0) mx-0 px-0 @endif">
            <li @if (!$agent->isMobile()) class="active" @endif >
                <div class="collapsible-header gradient-45deg-purple-deep-orange white-text">
                    My Tickets &nbsp;
                    @if ($agent->isMobile())
                        <span class="right mt-2" style="font-size: 6pt;vertical-align: bottom;">(click to expand)</span>
                    @endif
                </div>
                <div class="collapsible-body white @if (count(\App\Models\Ticket::where('status','<',2)->where('user_id',Auth::user()->id)->get()) > 0) mx-0 px-0 @endif">
                    @if (count(\App\Models\Ticket::where('status','<',2)->where('user_id',Auth::user()->id)->get()) > 0)
                        @foreach ($tickets as $ticket)
                            {{-- @if ($ticket->status != 2 || ($ticket->status == 2 && \Carbon\Carbon::parse($ticket->datetime)->addHours($ticket->duration))->gte(\Carbon\Carbon::now())) --}}
                            @if ($ticket->status < 2)
                                <div class="col s12 m6 l6">
                                    <div class="card 
                                        @if ($ticket->status === 0)
                                            gradient-45deg-blue-grey-blue-grey
                                        @else
                                            @if ($ticket->type === 'D')
                                            gradient-45deg-purple-deep-purple
                                            @else
                                                gradient-45deg-amber-amber
                                            @endif 
                                        @endif     
                                    gradient-shadow">
                                        <div class="card-content white-text mx-0 px-3" style="font-size:10pt">
                                            <span class="card-title truncate">
                                                @if ($ticket->type === 'D')
                                                    <span class="material-icons small">confirmation_number</span> 
                                                    Hot Desk
                                                @else
                                                    <span class="material-icons small">confirmation_number</span> 
                                                    Meeting Room
                                                @endif
                                            </span>
                                            <p>
                                                {{ \Carbon\Carbon::parse($ticket->datetime)->format('l,') }}
                                                <br>
                                                {{ \Carbon\Carbon::parse($ticket->datetime)->format('d F Y') }}
                                                <br>
                                                {{ \Carbon\Carbon::parse($ticket->datetime)->format('H:i') }} - {{ \Carbon\Carbon::parse($ticket->datetime)->addHours($ticket->duration)->format('H:i') }}
                                                <br>
                                                <div class="clearfix"></div>
                                                @switch($ticket->status)
                                                @case(0)
                                                    <span class=" badge grey darken-1 white-text left" @if(!$agent->isMobile()) style="font-size:8pt" @endif>Pending Approval<span>
                                                    @break
                                                @case(1)
                                                    <span class=" badge green darken-3 white-text left" @if(!$agent->isMobile()) style="font-size:8pt" @endif>Approved<span>
                                                    @break
                                                @case(2)
                                                    <span class=" badge red darken-3 white-text left" @if(!$agent->isMobile()) style="font-size:8pt" @endif>Used<span>
                                                    @break
                                            @endswitch
                                            </p>
                                        </div>
                                        <div class="card-action">
                                            @switch($ticket->status)
                                                {{-- @case(0)
                                                    <a href="#" class="lime-text text-accent-1">View Booking</a>
                                                    @break --}}
                                                @case(1)
                                                    <a href="{{ route('booking') }}" class="router-link truncate lime-text text-accent-1">Use Ticket</a>
                                                    @break
                                                @case(2)
                                                    <a href="#" class="truncate lime-text text-accent-1">View Booking</a>
                                                    @break
                                            @endswitch
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        @if (Auth::user()->approver_id)     
                            <blockquote class="grey-text"><i>you have no tickets reserved, please request a ticket from your approver by filling the form</i></blockquote>
                        @else
                            <blockquote class="grey-text"><i>you have no tickets reserved, please create a ticket by filling the form</i></blockquote>  
                        @endif
                    @endif
                <div class="clearfix"></div>
                </div>
            </li>
        </ul>
    </div>
    @if ($agent->isMobile())@endif
    <div class="col s12 m6 l7">
        <ul class="collapsible">
            <li class="active">
                <div class="collapsible-header gradient-45deg-indigo-light-blue white-text">
                    Ticket @if (Auth::user()->approver_id)     
                        Request
                    @endif Form
                </div>
                <div class="collapsible-body white">
                    {{-- <div id="basic-form" class="card card card-default scrollspy"> --}}
                        <form method="POST" action="{{ route('submit_ticket') }}">
                            @csrf
                            <div class="row">
                              <div class="input-field col s6 m5">
                                <input type="text" class="datepicker" id="fn" name="request_date" required>
                                <label for="fn" class="active">Ticket Date<sup class="red-text">*</sup></label>
                              </div>
                              <div class="input-field col s6 m4">
                                <input type="text" class="timepicker" id="ft" name="request_time" data-mintime="7:00 AM" data-maxtime="17:00" required>
                                <label for="ft" class="active">Ticket Time<sup class="red-text">*</label>
                              </div>
                              <div class="input-field col s6 m3">
                                <input type="number" id="dr" name="duration" value="1" min="1" max="5">
                                <label for="dr" class="active">Duration<sup>(in hours)</sup><sup class="red-text">*</label>
                              </div>
                            </div>
                            <div class="row">
                                @php
                                    $userPriv = \App\Models\ReservationPrivilege::where('user_id',Auth::user()->id)->where('zone_id',1)->first();
                                @endphp
                                {{-- {{ $userPriv }} --}}
                                <fieldset style="border:0">
                                    <legend class="grey-text darken-1" style="font-size:9pt">Ticket Type</legend>
                                    <p>
                                        <label>
                                          <input name="type" type="radio" value="D" class="with-gap" @if (!$userPriv) disabled @else checked @endif/>
                                          <span>Hot Desk</span>
                                        </label>
                                        <label>
                                            <input name="type" type="radio" value="MR" class="with-gap" @if (!$userPriv) checked @endif/>
                                            <span>Meeting Room</span>
                                        </label>
                                      </p>
                                </fieldset>
                                  
                            </div>
                            <div class="row">
                              <div class="input-field col s12 m6">
                                    @php
                                        $approver_id = \App\Models\User::find(Auth::user()->id)->approver_id;
                                        if ($approver_id) {
                                            $approver = \App\Models\User::find($approver_id)->name;
                                        }
                                    @endphp
                                    <input id="spv" type="text" value="{{ $approver ?? "-" }}" readonly>
                                    <label for="spv">Approver</label>
                                </div>
                                <div class="input-field col s12 m6">
                                    @php
                                      $cc_id = \App\Models\User::find(Auth::user()->id)->cc_id;
                                      if ($cc_id) {
                                          $cc = \App\Models\User::find($cc_id)->name;
                                      }
                                    @endphp
                                    <input id="spv" type="text" value="{{ $cc ?? "-" }}" readonly>
                                    <label for="spv">CC</label>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="input-field col s12">
                                  <textarea id="reqn" class="materialize-textarea" data-length="120" name="request_notes" required></textarea>
                                  <label for="reqn">
                                    @if (Auth::user()->approver_id)     
                                        Request Notes
                                    @else
                                        Remark
                                    @endif
                                    <sup class="red-text">*
                                    </label>
                                </div>
                            </div>
                            @if (Auth::user()->approver_id)
                                <blockquote class="grey-text lighten-2" style="border-color: #b71c1c;"><i> we will e-mail your approver regarding this request, please wait for their approvement before you can use your ticket</i></blockquote>
                            @endif
                            <div class="row">
                                @if (!Auth::user()->approver_id)
                                    <div class="input-field col s12">
                                        <button class="btn green waves-effect waves-light right" type="submit" name="action">Create Ticket
                                        <i class="material-icons right">send</i>
                                        </button>
                                    </div>
                                @else
                                    <div class="input-field col s12">
                                        <button class="btn cyan waves-effect waves-light right" type="submit" name="action">Submit Request
                                        <i class="material-icons right">send</i>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </form>
                      {{-- </div> --}}
                </div>
            </li>
        </ul>
    </div>
</div>

</div>
@endsection