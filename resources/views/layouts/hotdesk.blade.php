@if (!$desks)
    @php
    $desks = \App\Models\Desk::where('zone_id', 1)->where('coords','!=', null)->get();
    @endphp
@endif
<h4 class="grey-text darken-2 center">{{ $start_time->format('l, d F Y') }}</h4>
<h5 class="center grey-text darken-2">{{ $start_time->format('g:i A') }} - {{ $end_time->format('g:i A') }}</h5>
<div class="row">
    <div class="col s6 l3 offset-l1"><p><span class="left badge grey" style="min-width:20%"></span>&nbsp;Unavailable</p></div>
    <div class="col s6 l3 offset-l0"><p><span class="left badge red" style="min-width:20%"></span>&nbsp;Booked</p></div>
    <div class="col s6 l3 offset-l0"><p><span class="left badge green" style="min-width:20%"></span>&nbsp;Available</p></div>
</div>

<img class="responsive-img" src="{{ asset('images/maps/hot_desk_r7.png') }}" usemap="#hotDesks" id="hotDesksImg" oncontextmenu="return false;" style="pointer-events: initial; @if($agent->isMobile()) min-width:120%; @else min-width:90%; @endif"
>
<map id="hotDesksMap" name="hotDesks">
    @foreach ($desks as $desk)
        <area shape="rect" id="desk-{{ $desk->id }}" coords="{{ $desk->coords }}" href="#" alt="{{ $desk->desk_name }}" data-tooltip="{{ $desk->desk_name }}" title="{{ $desk->desk_name }}" data-key="{{ $desk->id }}" data-status="{{ $desk->status }}" data-availability="{{ $desk->availability }}" data-name="{{ $desk->desk_name }}"/>
    @endforeach
</map>