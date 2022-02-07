$('#pat').on('change', function () 
{
    let date = new Date($(this).val()).getTime();
    let url =  '/ajax/getCalendarByDate/'+date;
    console.log(date);
    $.ajax({
        type: "GET",
        url: url,
        dataType: "html",
        beforeSend : function(){
            preload(1);
        },
        success: function (response) {
            $('#calendar-content').html(response);
            preload(0);
        }
    });

});
$('#pat2').on('change', function () 
{
    let date = new Date($(this).val()).getTime();
    let url =  '/ajax/getMRCalendarByDate/'+date;
    console.log(date);
    $.ajax({
        type: "GET",
        url: url,
        dataType: "html",
        beforeSend : function(){
            preload(1);
        },
        success: function (response) {
            $('#calendar-content').html(response);
            preload(0);
        }
    });

});