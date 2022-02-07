@php
$cTime = \Carbon\Carbon::now()->subHours(1);

$timePoints = ['07:00','08:00','09:00','10:00','11:00',
                '12:00','13:00','14:00','15:00','16:00',
                '17:00','18:00','19:00',
                ];

@endphp
<div class="fixed-side center gradient-45deg-indigo-purple white-text pl-5 pr-5" >
<br>
<a href="#" id="navigate-calendar-previous" class="white-text left mt-2" style="font-size: 20px;"><i class="material-icons">chevron_left</i></a>
<a href="#" id="navigate-calendar-next" class="white-text right mt-2" style="font-size: 20px;"><i class="material-icons">chevron_right</i></a>
<h5 class="white-text">{{ \Carbon\Carbon::parse($dtSelected)->format("Y M d") }}</h5>
<br><div class="clearfix"></div>
</div>

<div id="table-scroll" class="table-scroll">
<table id="main-table" class="main-table bordered">
  <thead>
    <tr>
      <th scope="col" class="grey lighten-1"><sup>desk</sup>/<sub>time</sub></th>
        @foreach ($timePoints as $tp)
            @php
                $xpTime = explode(':',$tp);
                $tpTime = \Carbon\Carbon::parse($dtSelected)->hour($xpTime[0])->minute($xpTime[1])->second(0);    
                $tpPassed = $tpTime->gt($cTime); 
            @endphp
            @if (!$tpPassed)
                 <th class="grey darken-1 white-text tscroll-header">{{ $tp }}</th>           
            @else
                @if ($tpTime->minute(0)->second(0)->diffInHours($cTime) === 0)
                    <th class="pulse tscroll-header">{{ $tp }}</th>       
                @else
                    <th class="grey lighten-1 tscroll-header">{{ $tp }}</th>       
                @endif
            @endif
      @endforeach
    </tr>
  </thead>
  <tbody>
      @foreach ($desks as $desk)
        @php
            $tpPrint = [];    
            $toExclide = [];
        @endphp
        <tr>
            <th @if ($desk->availability) class="gradient-45deg-amber-amber white-text" @else class="grey darken-2 white-text" @endif >{{ $desk->desk_name }}</th>
            @foreach ($timePoints as $tpLoopKey => $tp)
                @php
                    $xpTime = explode(':',$tp);
                    $tpTime = \Carbon\Carbon::parse($dtSelected)->hour($xpTime[0])->minute($xpTime[1])->second(0);
                    
                    $tpPassed = $tpTime->gt($cTime); 
                    $reserve = null;
                    $colspan = 0;
                    $arr = ['loopKey'=>$tpLoopKey,'colspan' => 0,'reserve'=>null, 'time'=>$tpTime,'tpPassed'=>$tpPassed];
                    array_push($tpPrint, $arr);
                @endphp
            @endforeach
            @foreach ($tpPrint as $tpPrintKey => $tpp)
                
                @php
                    $fReserve = \App\Models\Reservation::where('datetime_start',\Carbon\Carbon::parse($tpp['time'])->format('Y-m-d H:i:s'))->get();
                    if ($fReserve) {
                        $timeFix = $tpp['time'];
                        foreach ($fReserve as $key => $found) {
                            if ($found && $desk->id == $found->desk_id) {
                                $reserve = $found;
                                $colspan = $reserve->duration;
                                $arr = ['colspan' => $colspan,'reserve'=>$reserve, 'time'=>$timeFix,'tpPassed'=>$tpPassed];
                                $tpPrint[$tpPrintKey]['colspan'] =  $colspan;    
                                $tpPrint[$tpPrintKey]['reserve'] =  $reserve;    
                            } 
                        }
                    }
                @endphp
            @endforeach
            
            @foreach ($tpPrint as $loopKey => $data)
                @php
                    if($data['colspan'] != 0)
                    {
                        $spannedData = [];
                        $startTime = \Carbon\Carbon::parse($data['time']);
                        $x = 1;
                        for ($i=1; $i < $data['colspan']; $i++) { 
                            $spannedTime = $startTime->addHours(1);
                            $spannedKey = array_keys(array_combine(array_keys($tpPrint), array_column($tpPrint, 'time')),$spannedTime);
                            unset($tpPrint[$spannedKey[0]]);
                            $x++;
                        }   
                        $tpPrint[$loopKey]['spannedData'] = $spannedData;
                    }
                @endphp
            @endforeach
            @if ($desk->availability)
                @foreach ($tpPrint as $data)
                    <td colspan="{{ $data['colspan'] }}" 
                    @if ($data['reserve'])
                        @if ($data['reserve']['start_time'])
                            class="red darken-2 center white-text"
                        @else
                            class="deep-orange darken-1 center white-text"
                        @endif
                    @else
                        @if (!$data['tpPassed'])
                            class="center yellow lighten-3 calendar-item"
                        @else
                            @if (\Carbon\Carbon::parse($data['time'])->minute(0)->second(0)->diffInHours($cTime) === 0)
                                class="center light-green lighten-2"
                            @else
                                class="center"
                            @endif
                        @endif
                    @endif>
                        @if ($data['reserve'])
                            @if (!$data['reserve']['start_time'] && $data['reserve']['user_id'] == Auth::user()->id && \Carbon\Carbon::parse($data['reserve']['datetime_start'])->isSameDay(\Carbon\Carbon::now()))
                                <a href="{{ route('check_in',$data['reserve']['id']) }}" class="waves-effect white-text checkInBtn tooltipped right" data-tooltip="Check In"
                                    id="res-{{ $data['reserve']['id'] }}"
                                    data-desk="{{ \App\Models\Desk::find($data['reserve']['desk_id'])->desk_name }}"
                                    data-date="{{ \Carbon\Carbon::parse($data['reserve']['datetime_start'])->format('Y-m-d') }}"
                                    data-time="{{ \Carbon\Carbon::parse($data['reserve']['datetime_start'])->format('H:i') }}"
                                >
                                    <i class="white-text material-icons right ">assignment_turned_in</i>
                                </a> 
                            @endif
                            {{ \App\Models\User::find($data['reserve']['user_id'])->name }}
                            @if ($data['reserve']['user_id'] == Auth::user()->id || Auth::user()->role == 'ADMIN') 
                                <a href="#" class="white-text right deleteReservation tooltipped" data-id="{{ $data['reserve']['id'] }}" data-tooltip="Cancel Reservation">
                                    <i class="material-icons right">clear</i>
                                </a>
                            @endif
                        @endif
                    </td>
                @endforeach
            @else
                <td colspan="15" class="grey lighten-1 calendar-item"></td>
            @endif
        </tr>
      @endforeach
  </tbody>
</table>
</div>
<div class="clearfix"></div>
<script>
    $('.tooltipped').tooltip();

    $(".deleteReservation").on('click', function (e) {
        e.preventDefault();
        var resID = $(this).data('id');
        console.log('masuk');
        Swal.fire({
            icon: 'warning',
            title : 'Confirm Cancellation',
            text : 'Are you sure to delete this reservation?',
            showCancelButton: true,
            cancelButtonColor: '#d32f2f'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    method : "GET",
                    url : "{{ route('index') }}/ajax/cancelReservation/"+resID,
                    dataType : "JSON",
                    beforeSend: function(){
                        $(".main-loader").show();
                    },
                    success : function(response){
                        $(".main-loader").hide();
                        Swal.fire(response.title,response.body,response.type).then(()=>{
                            $(".main-loader").show();
                            window.location = response.redirect
                        });
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        $(".main-loader").hide();
                        Swal.showValidationmessage(xhr.responseText);
                        console.log(thrownError);
                    }
                });
            } 
        });
    });
    $(".checkInBtn").on('click', function (event) {
        event.preventDefault();

        Swal.fire({
            icon: "info",
            title : "are you sure to check in?",
            text  : "Confirm check in for "+$(this).data('desk')+", "+$(this).data('date')+" "+$(this).data('time')+"?"
        }).then((result)=>{
            if (result.isConfirmed) {
                $(".main-loader").show();
                $.ajax({
                    type: "GET",
                    url: $(this).attr('href'),
                    dataType: "JSON",
                    beforeSend: function(params) {
                        $(".main-loader").show();   
                    },
                    success: function (response) {
                        $(".main-loader").hide();
                        Swal.fire({
                            title : response.title,
                            text : response.body,
                            icon : response.type
                        }).then(() => {
                            window.location = response.redirect
                        });           
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        $(".main-loader").hide();
                        Swal.fire(xhr.status, xhr.responseText, "error");
                        console.log(thrownError);
                    }
                });
            }
        });
    });
    $('#navigate-calendar-previous').on('click',function (event) {
        event.preventDefault();
        
        let date = new Date(new Date().setDate(new Date($('.datepicker').val()).getDate() - 1));
        
        $('.datepicker').trigger('click');
        $('.datepicker').datepicker('setDate',date);
        $('.datepicker-done').trigger('click');
    });
    $('#navigate-calendar-next').on('click',function (event) {
        event.preventDefault(); 
        let date = new Date(new Date().setDate(new Date($('.datepicker').val()).getDate() + 1));
        
        $('.datepicker').trigger('click');
        $('.datepicker').datepicker('setDate',date);
        $('.datepicker-done').trigger('click');
    });
</script>