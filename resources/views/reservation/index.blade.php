@extends('layouts.pagewrapper')
@section('page')
@php
$demoTickets = (object) [['date' => \Carbon\Carbon::now(),'type'=>'M', 'status' =>0],['date' => \Carbon\Carbon::now()->addDays(2),'type'=>'D', 'status' => 1],['date' => \Carbon\Carbon::now()->addDays(5),'type'=>'M', 'status' =>2],]
@endphp
<div class="col s12 m12 l12">
<br>
<div class="card center">
    <div class="card-content">
        <p class="caption ">Pick a desk or Meeting Room</p>
      </div>
</div>
<div class="row">
    <div class="col s12 m6 l5">
        <ul class="collapsible mx-0 px-0" id="bookingFormCollapsible">
            <li class="active">
                <div class="collapsible-header gradient-45deg-purple-deep-orange white-text">
                    Form &nbsp;
                    @if ($agent->isMobile())
                        <span class="right mt-2" style="font-size: 6pt;vertical-align: bottom;">(click to expand)</span>
                    @endif
                </div>
                <div class="collapsible-body white">
                    <ul class="stepper linear">
                        <li class="step active">
                           <div class="step-title waves-effect">Pick a ticket</div>
                           <div class="step-content">
                              <select name="ticket_select" class="browser-default" id="ticketSelect">
                                  <option selected disabled>Available Tickets</option>
                                  @foreach (\App\Models\Ticket::all() as $ticket)   
                                    @if (Auth::user() && $ticket->status == 1 && $ticket->user_id == Auth::user()->id)              
                                        {{-- @php
                                            switch ($ticket->type) {
                                                case 'MR':
                                                    $ticket->type = 'Meeting Room';
                                                    break;
                                                case 'D':
                                                    $ticket->type = 'Hot Desk';
                                                    break;
                                            }
                                        @endphp                     --}}
                                        <option value="{{ $ticket->id }}" 
                                                data-type="{{ $ticket->type }}"
                                                data-day="{{ \Carbon\Carbon::parse($ticket->datetime)->format('l,') }}"
                                                data-date="{{ \Carbon\Carbon::parse($ticket->datetime)->format('d F Y') }}"
                                                data-datetime-start="{{ \Carbon\Carbon::parse($ticket->datetime)->format('Y-m-d H:i') }}"
                                                data-datetime-end="{{ \Carbon\Carbon::parse($ticket->datetime)->addHours($ticket->duration)->format('Y-m-d H:i') }}"
                                        >
                                        {{ $ticket->type }} {{ \Carbon\Carbon::parse($ticket->datetime)->format('H:i') }} - {{ \Carbon\Carbon::parse($ticket->datetime)->addHours($ticket->duration)->format('H:i') }} | {{ \Carbon\Carbon::parse($ticket->datetime)->format('d M Y') }}
                                    </option>
                                    @endif
                                  @endforeach
                              </select>
                              <div class="step-actions">
                                 <!-- Here goes your actions buttons -->
                                 <button id="step1-next" class="waves-effect waves-dark btn next-step">Next <i class="material-icons right">expand_more</i></button>
                              </div>
                           </div>
                        </li>
                        <li class="step ">
                            <div class="step-title waves-effect" id="step-title-2">Pick a desk</div>
                            <div class="step-content">
                                <button class="waves-effect btn teal" type="button" id="getDesksButton">View Available Desks</button>
                                <br>
                                <div class="clearfix"></div>
                                <div class="row">
                                    <br>
                                    <div class="input-field col s12">
                                        <input type="text" readonly id="deskInputDisplay">
                                        <label for="deskInputDisplay" class="active" id="step-label-2">Desk</label>
                                    </div>
                                </div>
                                <div class="step-actions">
                                    <!-- Here goes your actions buttons -->
                                    <button class="btn waves-effect waves-dark previous-step">Previous <i class="material-icons left">expand_less</i></button>
                                    <button class="btn waves-effect waves-dark next-step">Next <i class="material-icons right">expand_more</i></button>
                                </div>
                            </div>
                         </li>
                         <li class="step ">
                            <div class="step-title waves-effect">Confirm your booking</div>
                            <div class="step-content">
                                <form action="{{ route('submit_reservation') }}" method="post">
                                    @csrf
                                    <input type="hidden" readonly id="deskInput" name="desk_id">
                                    <input type="hidden" readonly id="ticketInput" name="ticket_id">
                                    <div class="row">
                                        <br>
                                        <label>Ticket</label>
                                        <div id="selectedTicketContainer"></div>
                                    </div>
                                    <div class="row">
                                        <label class="active">Desk</label>
                                        <div class="input-field col s12">
                                            <input type="text" readonly id="deskInputDisplay2">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="active">Start Time</label>
                                        <div class="input-field col s12">
                                            <input type="text" readonly id="datetimestartInputDisplay">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <label class="active">Finish Time</label>
                                        <div class="input-field col s12">
                                            <input type="text" readonly id="datetimeendInputDisplay">
                                        </div>
                                    </div>
                                    <div class="step-actions">
                                        <button class="waves-effect waves-dark btn previous-step" type="button">Previous<i class="material-icons right">expand_less</i></button>
                                        <button class="waves-effect waves-dark btn green" type="submit">Submit<i class="material-icons left">event_available</i></button>
                                    </div>
                                </form>
                            </div>
                        </li>
                     </ul>
                <div class="clearfix"></div>
                </div>
            </li>
        </ul>
    </div>
    @if ($agent->isMobile())@endif
    <div class="col s12 m6 l7">
        <ul class="collapsible"  id="bookingDesksCollapsible">
            <li @if (!$agent->isMobile()) class="active" @endif>
                <div class="collapsible-header gradient-45deg-indigo-light-blue white-text">Ticket Request Form</div>
                <div class="collapsible-body white px-0 mx-0" style="overflow-x: hidden">
                    <div id="desksContainer">
                        
                    </div>
                    <div class="clearfix"></div>
                </div>
            </li>
        </ul>
    </div>
</div>

</div>
@endsection