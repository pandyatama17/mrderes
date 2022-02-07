@php
$cTime = \Carbon\Carbon::now()->subHours(1);

$timePoints = ['07:00','08:00','09:00','10:00','11:00',
                '12:00','13:00','14:00','15:00','16:00',
                '17:00','18:00','19:00',
                ];

@endphp
<div class="fixed-side center gradient-45deg-indigo-purple white-text pl-5 pr-5" >
<br>
<a href="#" class="white-text left mt-2" style="font-size: 20px;"><i class="material-icons">chevron_left</i></a>
<a href="#" class="white-text right mt-2" style="font-size: 20px;"><i class="material-icons">chevron_right</i></a>
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
        {{-- desk #{{ $desk->id }} : {{ $desk->desk_name }} --}}
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
            {{-- orig: <br>
            [ --}}
            @foreach ($tpPrint as $tpPrintKey => $tpp)
                {{-- <br> --}}
                {{-- {{ \Carbon\Carbon::parse($tpp['time'])->format('H:i:s') }} <-> {{ \Carbon\Carbon::parse($tpPrint[$tpPrintKey]['time'])->format('H:i:s') }}, --}}
                @php
                    $fReserve = \App\Models\Reservation::where('datetime_start',\Carbon\Carbon::parse($tpp['time'])->format('Y-m-d H:i:s'))->get();
                    if ($fReserve) {
                        // echo json_encode($fReserve);
                        $timeFix = $tpp['time'];
                        foreach ($fReserve as $key => $found) {
                            if ($found && $desk->id == $found->desk_id) {
                                $reserve = $found;
                                $colspan = $reserve->duration;
                                $arr = ['colspan' => $colspan,'reserve'=>$reserve, 'time'=>$timeFix,'tpPassed'=>$tpPassed];
                                $tpPrint[$tpPrintKey]['colspan'] =  $colspan;    
                                $tpPrint[$tpPrintKey]['reserve'] =  $reserve;    
                            }
                            // else {
                            //     $arr = ['colspan' => 0,'reserve'=>null, 'time'=>$tpTime,'tpPassed'=>$tpPassed];
                            // }
                            // $tpPrint[$tpPrintKey] =  $arr;    
                        }
                    }
                @endphp
            @endforeach
            {{-- ]
            <br>
            processed: <br>
            [@foreach ($tpPrint as $tpPrintKey => $tpp)
                {{ \Carbon\Carbon::parse($tpp['time'])->format('H:i:s') }},
            @endforeach]
            <br>
            <br> --}}
            {{-- <br> <br> --}}
            {{-- @foreach ($timePoints as $tpLoopKey => $tp)
                @php
                   $xpTime = explode(':',$tp);
                    $tpTime = \Carbon\Carbon::parse($dtSelected)->hour($xpTime[0])->minute($xpTime[1])->second(0);
                    
                    $tpPassed = $tpTime->gt($cTime); 
                    $reserve = null;
                    $colspan = 0;
                    if (!$tpPassed) 
                    {
                        try {
                            $fReserve = \App\Models\ReservationHistory::where('datetime_start',$tpTime)->first();
                        } catch (\Throwable $th) {
                            $fReserve = \App\Models\Reservation::where('datetime_start',$tpTime)->first();
                        }
                        if ($fReserve && $desk->id == $fReserve->desk_id) {
                            $reserve = $fReserve;
                            $colspan = $reserve->duration;
                            $arr = ['loopKey'=>$tpLoopKey,'colspan' => $colspan,'reserve'=>$reserve, 'time'=>$tpTime,'tpPassed'=>$tpPassed];
                            
                        }
                        else {
                            $arr = ['loopKey'=>$tpLoopKey,'colspan' => 0,'reserve'=>null, 'time'=>$tpTime,'tpPassed'=>$tpPassed];
                        }
                        array_push($tpPrint, $arr);
                    }
                    else {
                        $arr = ['loopKey'=>$tpLoopKey,'colspan' => 0,'reserve'=>null, 'time'=>$tpTime,'tpPassed'=>$tpPassed];
                        array_push($tpPrint, $arr);
                        
                        $fReserve = \App\Models\Reservation::where('datetime_start',$tpTime)->get();
                        foreach ($fReserve as $key => $found) {
                            if ($found && $desk->id == $found->desk_id) {
                                $reserve = $found;
                                $colspan = $reserve->duration;
                                $arr = ['colspan' => $colspan,'reserve'=>$reserve, 'time'=>$tpTime,'tpPassed'=>$tpPassed];
                            }
                            else {
                                $arr = ['colspan' => 0,'reserve'=>null, 'time'=>$tpTime,'tpPassed'=>$tpPassed];
                            }
                            array_replace($tpPrint[$tpLoopKey], $arr);
                        }
                    }
                @endphp
            @endforeach --}}
            {{-- {{ json_encode($tpPrint) }} --}}
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
                            if ($spannedTime) {
                                echo \Carbon\Carbon::parse($spannedTime)."<br>";
                            }
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
                     style="border: 1px solid black"
                    @if ($data['reserve'])
                        class="red center white-text"
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
                        {{ \Carbon\Carbon::parse($data['time'])->format('H:i')}}
                        @if ($data['reserve'])
                            {{ \App\Models\User::find($data['reserve']['user_id'])->name }}
                            @if ($data['reserve']['user_id'] == Auth::user()->id || Auth::user()->role == 'ADMIN') 
                                <a href="#" class="white-text right deleteReservation" data-id="{{ $data['reserve']['id'] }}">
                                    <i class="material-icons">clear</i>
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

$(".deleteReservation").on('click', function (e) {
    e.preventDefault();
    var resID = $(this).data('id');
    console.log('masuk');
    Swal.fire({
        icon: 'warning',
        title : 'Confirm Cancellation',
        text : 'Are you sure to delete this reservation?',
        showCancelButton: true
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
                    Swal.fire(response.type,response.body,response.title).then(()=>{
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
</script>