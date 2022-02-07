<img src="{{ asset('images/maps/meeting_room_r1.png') }}" id="hotDesksImg" usemap="#meetingRoomMap" oncontextmenu="return false;" style="pointer-events: initial; @if($agent->isMobile()) max-width:90%; @else max-width:90%; @endif"
/>
<map id="hotDesksMap" name="meetingRoomMap">
    @foreach ($rooms as $room)
        <area shape="{{ $room->shape }}" id="room-{{ $room->id }}" coords="{{ $room->coords }}" href="#" alt="{{ $room->desk_name }}" data-tooltip="{{ $room->desk_name }}" title="{{ $room->desk_name }}" data-key="{{ $room->id }}" data-status="{{ $room->status }}" data-availability="{{ $room->availability }}" data-name="{{ $room->desk_name }}"/>
    @endforeach
</map>