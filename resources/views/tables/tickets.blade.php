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
                    <th>Action</th>
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