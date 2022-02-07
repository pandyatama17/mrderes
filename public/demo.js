$(document).ready(function(){
    // $(document).on('click','.router-link', function (event) {
    //     event.preventDefault();
    //     let url = $(this).attr('href');
    //     let page = $(this).data('page');
    //     let rawUrl =url.replace(assetsUrl,'');
    //     if(window.location.href != "{{ route('calendar') }}")
    //     {
    //         if($('#current-page').val() != url){         
    //             redir_route(url,page);
    //         }
    //     }
    //     else
    //     {
    //         $.ajaxSetup({
    //             headers: {
    //                 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //             }
    //         });
    //         $.ajax({
    //             type: "GET",
    //             url: "/ajax/routesession/set/"+rawUrl,
    //             dataType: "json",
    //             success: function (response) {
    //                 console.log(response);
    //             }
    //         });
    //         window.location.href = assetsUrl;
    //     }
    // });
    // $(window).on('load', function () {
        // console.log(top.location.href);
        if (top.location.pathname.includes('calendar')) {
            setTimeout(
            function() 
            {
                $('.datepicker').trigger('click');
                const date = datepicker.val();
                datepicker.datepicker('setDate',date);
                $('.datepicker-done').trigger('click');
            }, 1000);
        }
        else
        {
            setTimeout(
                function() 
                {
                    $(".main-loader").hide();
                }, 1000);
        }
    // });
    
    $("#content-form").hide();
    var stepper = document.querySelector('.stepper');
    if(stepper)
    {
        var stepperInstace = new MStepper(stepper, {
            // options
            firstActive: 0 // this is the default
         });
    }
    $(".select2").select2({
        dropdownAutoWidth: true,
        width: '100%'
    });
    $(".datatable").dataTable({
        
    });
    $('.timepicker').mdtimepicker({
        format: 'h:mm tt',
        // hourPadding: true,
        is24hour: true,
    });

    $(".mdtp__hour_holder > .mdtp__digit").on('click',function(){
        // console.log($(this).html());
        var sptm = $(this).children('span').html();
        console.log(sptm);
        // alert('pasd');
        // $(".mdtp__minute_holder > .mdtp__digit.rotate-90.marker").addClass('active');
        $('.timepicker').mdtimepicker('hide');
        $('.timepicker').mdtimepicker('setValue',sptm+':00');
    });
    $('.collapsible').collapsible();
    $('.tooltipped').tooltip();
    const min_date = new Date();
    const max_date = min_date.setDate(min_date.getDate()+7);
    if (top.location.pathname.includes('calendar')) {
        var datepicker = $('.datepicker').datepicker({
            container: 'body',
            disableDayFn: function(date) {
                if(date.getDay() > 0 && date.getDay() < 6) // getDay() returns a value from 0 to 6, 1 represents Monday
                    return false;
                else
                    return true;
              }
        });
    }
    else
    {
        var datepicker = $('.datepicker').datepicker({
            minDate: new Date(),
            // max_date: max_date,
            container: 'body',
            disableDayFn: function(date) {
                if(date.getDay() > 0 && date.getDay() < 6) // getDay() returns a value from 0 to 6, 1 represents Monday
                    return false;
                else
                    return true;
              }
        });
    }
    
    

    
    $('.modal').modal();
    $('.materialboxed').materialbox({
        onOpenStart:function(){
                $("#zone-red-map").removeClass('hide');
            },
        onCloseStart:function(){
            $("#zone-red-map").addClass('hide');
        }
    });
    // $(".main-table").clone(true).appendTo('#table-scroll').addClass('clone');   

    $(".select-desk").on('click',function(){
        let zone = $(this).data('zone');
        let desk = $(this).data('desk');

        $("#deskInput").val(desk);
        $("label[for='deskInput']").addClass('active');
        // alert($("#zoneSelect").val());
        $("#zoneSelect").val(zone).change();
        $('#zoneSelect').formSelect() ;

        // setTimeout(function(){$("#sidenavTrigger").trigger('click')},100);
        $("#slide-out-right").sidenav('open');
        // alert(zone + " " + desk)
    });

    $("#map-trigger").on('click',function(e){
        e.preventDefault();
        $("#zone-red-map").trigger('click');
    });
    $('#ticketSelect').on('change',function()
    {
        var ticketType = $(this).find(':selected').data('type');
        var ticketDay = $(this).find(':selected').data('day');
        var ticketDate = $(this).find(':selected').data('date');
        var card = '<div class="col s12 m12 l12"><div class="card gradient-shadow ';
        if (ticketType == 'Meeting Room') {
            card += 'gradient-45deg-amber-amber">';
            $("#step-title-2").text('Pick a room');
            $("#step-label-2").text('Room');
        }
        else
        {
            $("#step-title-2").text('Pick a desk');
            $("#step-label-2").text('Desk');
            card += 'gradient-45deg-purple-deep-purple">';
        }
        card +='<div class="card-content white-text mx-0 px-3" style="font-size:10pt"> <span class="card-title truncate"><span class="material-icons small">confirmation_number</span>';
        card += ticketType;
        card += '<p style="font-size:10pt">'+ticketDay+ticketDate+'<br><div class="clearfix"></div>';
        card += '<span class=" badge green darken-3 white-text left" ';
        if (isMobile) {
            card += 'style="font-size:8pt"'
        }
        card += '>Approved<span>';
        
        $("#selectedTicketContainer").html(card);
        $("#ticketInput").val($(this).val());
        $("#datetimestartInputDisplay").val($(this).find(':selected').data('datetime-start'));
        $("#datetimeendInputDisplay").val($(this).find(':selected').data('datetime-end'));
    });
    $("#step1-next").on('click', function(){
        $("#getDesksButton").trigger('click');
        $("#deskInputDisplay").focus();
    });
    $("#getDesksButton").on('click', function(){
        var ticket = $('#ticketSelect').val();
        if (ticket) {
            $.ajax({
                url : 'ajax/getDesksByTicket/'+ticket,
                type : 'GET',
                beforeSend: function(){
                    $(".main-loader").show();
                },
                success: function(response){
                    $("#desksContainer").html('');
                    $("#desksContainer").html(response);
                    reload_js(assetsUrl+'mapster.js');
                    if (isMobile) {
                        $("#bookingFormCollapsible").collapsible('close',0);
                        $("#bookingDesksCollapsible").collapsible('open',0);

                    }
                    $(".main-loader").hide();
                }
            })
        }
        else{
            Swal.fire('Unavailable', 'please pick a ticket!', 'error');
        }
    });
    $("#userIdSelect").on('change', function () 
    {
        var data = $(this).select2('data');
        var text;
        console.log(data[0].text);
        if ($(this).val() == '1') 
        {
            text = $("#manualNameInput").val();   
        }
        else
        {
            text = data[0].text;
        }
        $("#userNameConfirm").val(text);
    });
    $("#manualNameInput").on('change', function() {
        // $("#userInputDisplay").val($(this).val());
        $("#userIdSelect").trigger('change');
    });
    $("#adminReservationForm").on('submit', function(event){
        event.preventDefault();
        var _token = $("input[name='_token']").val();
        var userid = $("#userIdSelect").val();
        var name = $("#manualNameInput").val();
        var duration = $("#durationInput").val();
        var date = $("#dateInput").val();
        var time = $("#timeInput").val();
        var type = $("input[name='type']:checked").val();
        var reqn = $("#reqn").val();
        
        if(userid == null || (userid == 1 && name == '')){
            Swal.fire('Error!','Please fill in all the required fields!','error');
        }
        else
        {
            $.ajax({
                url : '/ajax/getDesksForAdmin/',
                type : 'POST',
                data : {_token:_token,userid:userid,name:name,date:date,time:time,type:type,duration:duration,request_notes:reqn},
                beforeSend: function(){
                    $(".main-loader").show();
                },
                success: function(response)
                {
                    console.log(response);
                    $("#desksContainer").html('');
                    $("#desksContainer").html(response);
                    reload_js(assetsUrl+'mapster.js');
                    if (isMobile) {
                        $("#bookingFormCollapsible").collapsible('close',0);
                        $("#bookingDesksCollapsible").collapsible('close',0);
    
                    }
                    let  card = '<div class="card ';
                    let ticketType = (type == "1" ? 'Hot Desk' : 'Meeting Room')
                    if (type == '2') {
                        card += 'gradient-45deg-amber-amber">';
                    }
                    else
                    {
                        card += 'gradient-45deg-purple-deep-purple">';
                    }
                    card +='<div class="card-content white-text mx-0 px-3" style="font-size:10pt"> <span class="card-title truncate"><span class="material-icons small">confirmation_number</span>';
                    card += ticketType;
                    card += '<p style="font-size:10pt">'+date+' '+time+'<br><div class="clearfix"></div>';
                    card += '<span class=" badge green darken-3 white-text left" ';
                    card += '>Approved<span>';
                    $("#ticketPreview").html(card);
                    $("#remarkDisplay").html(reqn);
                    $("#durationConfirm").val(duration);
                    $("#dateConfirm").val(date);
                    $("#timeConfirm").val(time);
                    $("#typeConfirm").val(type);
                    $("#remarkConfirm").val(reqn);
                    $(".main-loader").hide();
                }
            });
        }
        
    });
    $("#adminAddUserTrigger").on('click', function (event) 
    {
        var _token = $("input[name='_token']").val();
        $("#content-list").hide();
        $('#userForm').trigger("reset"); //Line1
        $('#userForm select').trigger("change"); //Line2
        $("input[name='_token']").val(_token);
        $("#idInput").val('');
        $("#content-form").show();
        window.M.updateTextFields();
    });
    $(".toggleUserStatusTrigger").on('click', function (event) {
        event.preventDefault();
        Swal.fire({
            title: 'Confirm status change for '+$(this).data('name'),
            text: 'please type "CONFIRM" to proceed',
            input: 'text',
            inputAttributes: {
                autocapitalize: 'off'
            },
            confirmButtonText: 'Confirm Action',
            preConfirm: (confirmation) => {
                if (confirmation == 'CONFIRM') {
                    $.ajax({
                        type: "GET",
                        url: assetsUrl+'admin/user/changeStatus/'+$(this).data('id'),
                        dataType: "JSON",
                        beforeSend: function(){
                            $(".main-loader").show();
                        },
                        success: function (response) {
                            // return response;
                            $(".main-loader").hide();
                            Swal.fire({
                                title : response.title,
                                text : response.body,
                                icon : response.type
                            }).then(() => {
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
                else
                {
                    $(".main-loader").hide();
                    Swal.fire('Failed!','Please type "CONFIRM" with uppercased letters!','error');
                }
            }
        });
    });
    $('#adminUserListTrigger').on('click', function () {
        $("#content-form").hide();
        $("#content-list").show();
        
    });
    $(".editUserTrigger").on('click', function (e) {
        e.preventDefault();
        
        $(".main-loader").show();

        console.log($(this).data());
        $("#adminAddUserTrigger").trigger('click');
        
        $("#idInput").val($(this).data('id'));
        $("#nikInput").val($(this).data('nik'));
        $("#nameInput").val($(this).data('name'));
        $("#emailInput").val($(this).data('email'));
        $("#phoneInput").val($(this).data('phone'));
        
        $("#roleInput").val($(this).data('role'));
        $("#roleInput").trigger('change');
        $("#division_idInput").val($(this).data('division_id'));
        $("#division_idInput").trigger('change');
        $("#approverInput").val($(this).data('approver_id'));
        $("#approverInput").trigger('change');
        $("#ccInput").val($(this).data('cc_id'));
        $("#ccInput").trigger('change');

        $(".main-loader").hide();
    });
    $("#userIdSelect").on('change', function () {
        if ($(this).val() == '1') 
        {
            $("#manualUserNameCol").slideDown();
        }
        else
        {
            $("#manualUserNameCol").slideUp();
        }
        $("#userIdConfirm").val($(this).val());
    });
    
    $('#nowBtn').on('click', function () {
        let date = new Date();
        date.setMinutes(0);
        date.setSeconds(0);
        let time = formatAMPM(date);
        $('#dateInput').datepicker('setDate', date);
        $('#dateInput').datepicker('setInputValue',date);
        $('#timeInput').mdtimepicker('setValue',time);

        window.M.updateTextFields();
    });
    
    $("#adminConfirmReservationForm").on('submit', function (event) 
    {
        event.preventDefault();

        var _token = $("input[name='_token']").val();
        var userid = $("#userIdConfirm").val();
        var duration = $("#durationConfirm").val();
        var date = $("#dateConfirm").val();
        var time = $("#timeConfirm").val();
        var type = $("#typeConfirm").val();
        var reqn = $("#remarkConfirm").val();
        var desk = $("#remarkConfirm").val();
        var desk_id = $("#deskInput").val();
        var user_name = $("#userNameConfirm").val();

        $.ajax({
            method : 'POST',
            url : $(this).attr('action'),
            data : {_token:_token,user_id:userid,date:date,time:time,type:type,duration:duration,request_notes:reqn,desk:desk,desk_id:desk_id,user_name:user_name},
            dataType: 'json',
            beforeSend: function(){
                $(".main-loader").show();
            },
            success: function(response){
                $(".main-loader").hide();
                // const res = $.parseJSON(response);
                Swal.fire({
                    title : response.title,
                    text : response.body,
                    icon : response.type
                }).then((result) => {
                    window.location = response.redirect
                  });                
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $(".main-loader").hide();
                // Swal.fire(xhr.status, xhr.responseText, "error");
                console.log(thrownError);
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
    $("#generalForm").on('submit', function (event) {
        event.preventDefault();
        const data = $(this).serializeObject();
        // console.log(data);
        $.ajax({
            method : 'POST',
            url : $(this).attr('action'),
            data : data,
            dataType: 'json',
            beforeSend: function(){
                $(".main-loader").show();
            },
            success: function(response){
                $(".main-loader").hide();
                // const res = $.parseJSON(response);
                Swal.fire({
                    title : response.title,
                    text : response.body,
                    icon : response.type
                }).then((result) => {
                    window.location = response.redirect
                  });                
            },
            error: function (xhr, ajaxOptions, thrownError) {
                $(".main-loader").hide();
                // Swal.fire(xhr.status, xhr.responseText, "error");
                console.log(thrownError);
            }
        });
    });
    $('#passwordForm').on('submit', function (event) {
        event.preventDefault();
        var _token = $("input[name='_token']").val();
        var oldpswd = $("#oldpswd").val();
        var newpswd = $("#newpswd").val();
        var repswd = $("#repswd").val();

        if (newpswd == repswd) {
            $.ajax({
                method : 'POST',
                url : $(this).attr('action'),
                data : {_token:_token,oldpswd:oldpswd,newpswd:newpswd,repswd:repswd},
                dataType: 'json',
                beforeSend: function(){
                    $(".main-loader").show();
                },
                success: function(response){
                    $(".main-loader").hide();
                    // const res = $.parseJSON(response);
                    Swal.fire({
                        title : response.title,
                        text : response.body,
                        icon : response.type
                    }).then((result) => {
                        window.location = response.redirect
                      });                
                },
                error: function (xhr, ajaxOptions, thrownError) {
                    $(".main-loader").hide();
                    // Swal.fire(xhr.status, xhr.responseText, "error");
                    console.log(thrownError);
                }
            });
        }
        else
        {
            Swal.fire('Change password failed!', 'Password confirmation does not match! please check again', "error");        
        }
    });

    $('.sortTable').on('click', function (event) {
        event.preventDefault();
        $.ajax({
            type: "GET",
            url: assetsUrl+"ajax/getReservations/"+$(this).attr('href'),
            dataType: "html",
            beforeSend: function(){
                $('.main-loader').show();
            },
            success: function (response) {
                $('#myReservationsTable').html(response);
                reload_js(assetsUrl+'app-assets/js/scripts/app-contacts.js');
                reload_js(assetsUrl+'demo.js');
                $('.main-loader').hide();
            }
        });
    });

    $('.sortUsers').on('click', function (event) {
        event.preventDefault();
        $("#content-form").hide();
        $("#content-list").show();
        $.ajax({
            type: "GET",
            url: assetsUrl+"ajax/getUsers/"+$(this).attr('href'),
            dataType: "html",
            beforeSend: function(){
                $('.main-loader').show();
            },
            success: function (response) {
                $('#content-list').html(response);
                reload_js(assetsUrl+'app-assets/js/scripts/app-contacts.js');
                reload_js(assetsUrl+'demo.js');
                $('.main-loader').hide();
            }
        });
    });

    $('.sortTickets').on('click', function (event) {
        event.preventDefault();
        $.ajax({
            type: "GET",
            url: assetsUrl+"ajax/getTickets/"+$(this).attr('href'),
            dataType: "html",
            beforeSend: function(){
                $('.main-loader').show();
            },
            success: function (response) {
                $('#ticketsTable').html(response);
                reload_js(assetsUrl+'app-assets/js/scripts/app-contacts.js');
                reload_js(assetsUrl+'demo.js');
                $('.main-loader').hide();
            }
        });
    });
});

function capitalize(string){
    return string.charAt(0).toUpperCase() + string.slice(1);
}
function reload_js(src) {
    $('script[src="' + src + '"]').remove();
    $('<script>').attr('src', src).appendTo('head');
}
// M.updateTextFields();
    window.M.updateTextFields()
    // seEffect(() => {
    // window.M.updateTextFields()
    // },[])

function formatAMPM(date) {
  var hours = date.getHours();
  var minutes = date.getMinutes();
  var ampm = hours >= 12 ? 'PM' : 'AM';
  hours = hours % 12;
  hours = hours ? hours : 12; // the hour '0' should be '12'
  minutes = minutes < 10 ? '0'+minutes : minutes;
  var strTime = hours + ':' + minutes + ' ' + ampm;
  return strTime;
}

function redir_route(url,page)
{
    $.ajax({
        type: "GET",
        url: url,
        dataType: "html",
        beforeSend: function(){
            $(".main-loader").show();
            loadCrumbs(page);
        },
        success: function (response) {
            $('#main-content').html(response);
            $(".main-loader").hide();
            $("#current-page").val(url);
            if (isMobile) {
                $("#slide-out").sidenav('close');

            }
            reload_js(assetsUrl+'demo.js');
            
        }
    });
}
function preload(event)
{
    if (event == true || event == 'start') {
        $('.main-loader').show();
    }
    else
    {
        $('.main-loader').hide();
    }
}

function loadCrumbs(page) {
    $.ajax({
        type: "GET",
        url: assetsUrl+"ajax/getCrumbs/"+page,
        dataType: "JSON",
        success: function (crumbs) {
            // let crumbs = $.parseJSON(response);
            console.log(crumbs);
            $("#crumbs-page").html(crumbs.page);
            $('#crumbs').html('');
            $.each(crumbs.pages, function (key, crumb) { 
                 $('#crumbs').append('<li class="breadcrumb-item"><a href="'+crumb.url+'">'+crumb.title+'</a></li>');
            });
            $('#crumbs').append('<li class="breadcrumb-item active">'+crumbs.page+'</li>');
            document.title = crumbs.page;
        }
    });
}
$.fn.serializeObject = function() {
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name]) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};
