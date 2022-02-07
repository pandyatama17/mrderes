$(document).ready(function(){
    var areaItems = [];
    var item;
    $('#meetingRoomMap area').each(function(i, obj) {
      if ($(this).data('availability') == "0") {
        item = {},
        item['key'] = $(this).data('key').toString(),
        item['stroke'] = true,
        item['staticSelect'] = true,
        item['isDeselectable'] = false,
        item['selected'] = true,
        item['render_select'] = {
            fillOpacity: 1,
            fillColor: '9e9e9e',
            stroke : 1,
            strokeColor: '9e9e9e',
        },
        item['render_highlight'] = {
          fillOpacity: 1,
          fillColor: '9e9e9e',
          stroke : 0,
          strokeColor: '9e9e9e',
        }
        areaItems.push(item);
      }
      else{
        if ($(this).data('status') == "0" && $(this).data('status') == "0") {
            item = {},
            item['key'] = $(this).data('key').toString(),
            item['stroke'] = true,
            item['staticSelect'] = true,
            item['isDeselectable'] = false,
            item['selected'] = true,
            item['render_select'] = {
                fillOpacity: 1,
                fillColor: 'b71c1c',
                stroke : 1,
                strokeColor: 'b71c1c',
            },
            item['render_highlight'] = {
              fillOpacity: 1,
              fillColor: 'b71c1c',
              stroke :  1,
              strokeColor: 'b71c1c',
          }
        
        areaItems.push(item);
      }}
    });
    console.log(areaItems);
    var selectedBefore;
    var selectedCurrent;
    var mapper = $('#hotDesksImg');
    mapper.mapster({
        mapKey: 'data-key',
        fillColor: 'ff0000',
        fillOpacity: 0.3,
      isSelectable: true ,
      tooltip: 'data-tooltip',
      render_highlight: {
          strokeWidth: 0,
          fillColor: 'eeff41',
          fillOpacity: .5,
          mapkey: 'data-key',
          stroke: false,
      },
      render_select: {
          fillColor: 'ffff00',
          strokeWidth: 4,
          fillOpacity: 0,
          stroke: true,
      },
      areas: areaItems,
      onClick:function(e) {
        selectedCurrent = e.key;
        console.log(e);
        var status = $("#desk-"+selectedCurrent).data('status');
        var available = $("#desk-"+selectedCurrent).data('availability');
        if (status != 0 && available != 0) {
          if (selectedBefore) {
            mapper.mapster('set', false, selectedBefore);
          }
          selectedBefore = e.key;
        }
        else {
          // e.preventDefault();
          return false;
        }
        $("#deskInputDisplay").attr('readonly',false);
        $("#deskInputDisplay").val($(this).data('name'));
        $("#deskInputDisplay2").attr('readonly',false);
        $("#deskInputDisplay2").val($(this).data('name'));
        $("#deskInput").val($(this).data('key'));
        $("#deskInputDisplay").attr('readonly','readonly');
        $("#deskInputDisplay2").attr('readonly','readonly');
        if(isMobile)
        {
          $("#bookingFormCollapsible").collapsible('open',0);
          $("#bookingDesksCollapsible").collapsible('close',0);
        }
      }
    });

$('#hotDeskImg').bind("contextmenu",function(e){
  return false;
});
});
