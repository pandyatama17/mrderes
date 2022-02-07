<div class="datatable-search">
    <i class="material-icons mr-2 search-icon">search</i>
    <input type="text" placeholder="Search Reservation Date, time, or type" class="app-filter" id="global_filter">
</div>
<div id="button-trigger" class="card card card-default scrollspy border-radius-6 fixed-width">
    <div class="card-content p-0" id="myReservationsTable">
        <table id="data-table-contact" class="display">
            <thead>
                <tr>
                    <th class="all">Reservation Date</th>
                    <th class="all">Type</th>
                    <th class="all">Time</th>
                    <th>Desk/Room</th>
                    <th>Checked in</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservations as $res)
                    @php
                        $date = \Carbon\Carbon::parse($res->datetime_start)->format('Y-m-d');
                        $date_end = \Carbon\Carbon::parse($res->datetime_end)->format('Y-m-d');
                        $cTime = \Carbon\Carbon::now();
                        $time_start = \Carbon\Carbon::parse($res->datetime_start)->format('H:i');
                        $time_end = \Carbon\Carbon::parse($res->datetime_end)->format('H:i');
                        $desk = \App\Models\Desk::find($res->desk_id)
                    @endphp
                    <tr>
                        <td>{{ $date }} </td>
                        <td class="">
                            @switch($desk->zone_id)
                                @case(1)
                                    <span class="badge purple border-radius-6">Hot Desk</span>
                                    @break
                                @case(2)
                                    <span class="badge amber border-radius-6">Meeting Room</span>
                                    @break
                                @default                               
                            @endswitch
                            
                        </td>
                        <td>{{ $time_start }} - {{ $time_end }}</td>
                        <td>
                            @switch($desk->zone_id)
                                @case(1)
                                    <b class="black-text" style="font-weight: 1000">Desk {{ $desk->desk_name }}</b>
                                    @break
                                @case(2)
                                    <b class="black-text" style="font-weight: 1000">Room {{ $desk->desk_name }}</b>
                                    @break
                                @default                               
                            @endswitch
                        </td>
                        <td>
                            @if ($res->start_time)
                                {{ $res->start_time }}
                            @else
                                @if ($cTime->gt($date_end) || (!$res->start_time && \Carbon\Carbon::parse($res->datetime_start)->isSameDay($cTime)))
                                    @if (!$res->start_time)
                                        <a href="{{ route('check_in',$res->id) }}" class="waves-effect green-text checkInBtn" 
                                            id="res-{{ $res->id }}"
                                            data-desk="{{ $desk->desk_name }}"
                                            data-date="{{ $date }}"
                                            data-time="{{ $time_start }}"
                                        >
                                            <i class="green-text material-icons left">assignment_turned_in</i> | Check In
                                        </a>
                                    @else
                                        <span class="badge red border-radius-6">Expired</span>
                                    @endif
                                @else
                                    <span class="badge grey border-radius-6">not yet started</span>    
                                @endif
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
{{-- <script>
     $(document).ready(function () {
        $(".display").dataTable();
     });
</script> --}}