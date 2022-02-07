@extends('layouts.pagewrapper')
@section('page')
    <div class="col s12 m12 l12">
        <div class="card" style="border-radius: 10px;">
            <div class="card-content gradient-45deg-deep-orange-orange" style="border-top-left-radius: 10px; border-top-right-radius:10px;">
                <span class="card-title white-text"> Meeting Rooms</span>
                <a id="map-trigger" class="btn-floating waves-effect waves-light gradient-45deg-light-blue-cyan tooltipped hoverable z-depth-2 " data-position="bottom" data-tooltip="View Meeting Room Map" style="float:right" href="#"> 
                    <i class="material-icons">map</i>
                </a>
            </div>
            <div class="card-content">
                <div class="card-title">
                    <div class="input-field col s7 l6">
                        <input type="text" class="datepicker" value="{{ \Carbon\Carbon::now()->format("Y M d") }}" id="pat2">
                        <label for="pat2">Pick A Date</label>
                    </div>
                    <div class="input-field col s5 l2">
                        <input type="text" readonly  id="clock" value=" ">
                        <label for="clock" class="active">Current Time</label>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div id="calendar-content">
                    @php
                        $cTime = \Carbon\Carbon::now();

                        $timePoints = ['07:00','08:00','09:00','10:00','11:00',
                                    '12:00','13:00','14:00','15:00','16:00',
                                    '17:00','18:00','19:00','20:00','21:00',
                                    ];
                        
                    @endphp
                    {{-- <div class="fixed-side center gradient-45deg-indigo-purple white-text pl-5 pr-5" >
                        <br>
                        <a href="#" class="white-text left mt-2" style="font-size: 20px;"><i class="material-icons">chevron_left</i></a>
                        <a href="#" class="white-text right mt-2" style="font-size: 20px;"><i class="material-icons">chevron_right</i></a>
                        <h5 class="white-text">{{ \Carbon\Carbon::now()->format("Y M d") }}</h5>
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
                                        $tpTime = \Carbon\Carbon::parse()->hour($xpTime[0])->minute($xpTime[1])->second(0);    
                                        $tpPassed = $tpTime->gt($cTime); 
                                    @endphp
                                    @if (!$tpPassed)
                                        <th class="grey darken-1 white-text tscroll-header">{{ $tp }}</th>           
                                    @else
                                        <th class="grey lighten-1 tscroll-header">{{ $tp }}</th>       
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
                                    <th @if ($desk->availability) class="gradient-45deg-indigo-purple white-text" @else class="grey darken-2 white-text" @endif >{{ $desk->desk_name }}</th>
                                    @foreach ($timePoints as $tp)
                                        @php
                                           $xpTime = explode(':',$tp);
                                            $tpTime = \Carbon\Carbon::now()->hour($xpTime[0])->minute($xpTime[1])->second(0);
                                            
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
                                                    $arr = ['colspan' => $colspan,'reserve'=>$reserve, 'time'=>$tpTime,'tpPassed'=>$tpPassed];
                                                    
                                                }
                                                else {
                                                    $arr = ['colspan' => 0,'reserve'=>null, 'time'=>$tpTime,'tpPassed'=>$tpPassed];
                                                }
                                            }
                                            else {
                                                $fReserve = \App\Models\Reservation::where('datetime_start',$tpTime)->first();
                                                if ($fReserve && $desk->id == $fReserve->desk_id) {
                                                    $reserve = $fReserve;
                                                    $colspan = $reserve->duration;
                                                    $arr = ['colspan' => $colspan,'reserve'=>$reserve, 'time'=>$tpTime,'tpPassed'=>$tpPassed];
                                                }
                                                else {
                                                    $arr = ['colspan' => 0,'reserve'=>null, 'time'=>$tpTime,'tpPassed'=>$tpPassed];
                                                }
                                            }
                                            array_push($tpPrint, $arr);
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
                                                class="red center white-text"
                                            @else
                                                @if (!$data['tpPassed'])
                                                    class="center yellow lighten-3"
                                                @else
                                                    class="center"
                                                @endif
                                            @endif>
                                                @if ($data['reserve'])
                                                    {{ \App\Models\User::find($data['reserve']['user_id'])->name }}
                                                @endif
                                            </td>
                                        @endforeach
                                    @else
                                        <td colspan="15" class="grey lighten-1"></td>
                                        
                                    @endif
                                </tr>
                              @endforeach
                          </tbody>
                        </table>
                      </div>
                    <div class="clearfix"></div> --}}
                </div>
            </div>
        </div>
    </div>
    <script src="{{ asset('calendar.js') }}"></script>
    <script type="text/javascript">
        function showTime() {
            var d = new Date();
            var s = d.getSeconds();
            var m = d.getMinutes();
            var h = d.getHours();
            var time = ("0" + h).substr(-2) + ":" + ("0" + m).substr(-2) + ":" + ("0" + s).substr(-2);
      
          document.getElementById('clock').value = time;
        }
        setInterval(showTime, 1000);
      </script>
@endsection