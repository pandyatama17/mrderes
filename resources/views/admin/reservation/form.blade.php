@extends('layouts.pagewrapper')
@section('page')
<div class="col s12 m12 l12">
    <br>
    <div class="card center">
        <div class="card-content">
            <p class="caption ">Create a reservation Manually wih admin privilege</p>
          </div>
    </div>
    <div class="col s12 l5 ">
        <div class="row">
            <div class="col s12">
                <ul class="collapsible" id="adminFormCollapsible">
                    <li @if (!$agent->isMobile()) class="active" @endif >
                        <div class="collapsible-header gradient-45deg-purple-deep-orange white-text">
                            Reservation Form
                            @if ($agent->isMobile())
                                <span class="right mt-2" style="font-size: 6pt;vertical-align: bottom;">(click to expand)</span>
                            @endif
                        </div>
                        <div class="collapsible-body white">
                            <form action="{{ route('getDesksForAdmin') }}" method="POST" id="adminReservationForm">
                                @csrf
                                <div class="row">
                                    <div class="input-field col s12">
                                        <select name="user_id" class="select2 browser-default" id="userIdSelect" required>
                                            <option selected disabled>Select User...</option>
                                            <optgroup label="Admin Privilege">
                                                <option value="1" class="red">- Manual Input -</option>
                                            </optgroup>
                                            <optgroup label="Registered Users">
                                                @foreach ($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                @endforeach
                                            </optgroup>
                                        </select>
                                        <label for="">User</label>
                                    </div>
                                    <div class="input-field col s12" style="display: none" id="manualUserNameCol">
                                        <input type="text" name="manual_name" id="manualNameInput">
                                        <label for="">Name</label>
                                    </div>
                                </div>
                                <div class="row grey lighten-3 p-1 border-radius-5">
                                    <div class="input-field col s5 l5">
                                        <input type="text" name="date" class="datepicker" id="dateInput" required>
                                        <label for="">Date</label>
                                    </div>
                                    <div class="input-field col s3 l4">
                                        <input type="text" name="time" class="timepicker" id="timeInput" required>
                                        <label for="timeInput">Time</label>
                                    </div>
                                    <div class="input-field col s3">
                                        <input type="number"  name="duration" value="1" min="1" id="durationInput" required>
                                        <label for="">Duration<sup></sup></label>
                                    </div>
                                    <div class="input-field col s12">
                                        <button type="button" id="nowBtn" class="btn waves-effect btn-block"><i class="material-icons right">update</i>Now</button>
                                    </div>
                                </div>
                                <br>
                                <div class="clearfix"></div>
                                <div class="row">
                                    <fieldset style="border:0">
                                        <legend class="grey-text darken-1" style="font-size:9pt">Reservation Type</legend>
                                        <p>
                                            <label>
                                            <input name="type" type="radio" value="1" class="with-gap"/>
                                            <span>Hot Desk</span>
                                            </label>
                                            <label>
                                                <input name="type" type="radio" value="2" class="with-gap" checked/>
                                                <span>Meeting Room</span>
                                            </label>
                                        </p>
                                    </fieldset>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                    <textarea id="reqn" class="materialize-textarea" data-length="120" name="request_notes" id="remark" required></textarea>
                                    <label for="reqn">Remark</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <button type="submit" id="getDesksButton2" class="btn waves-effect btn-block teal darken-3"><i class="material-icons right">book</i><span id="pickDeskBtnLabel">Pick Desk</span></button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <div class="row">
            <div class="col s12">
                <ul class="collapsible" id="adminConfirmationCollapsible">
                    <li>
                        <div class="collapsible-header gradient-45deg-purple-light-blue white-text">Reservation Confirmation</div>
                        <div class="collapsible-body white px-0 mx-0" style="overflow-x: hidden">
                            <div class="col s12 l8 offset-l2" id="ticketPreview">
                            </div>
                            <div class="clearfix"></div>
                            <form action="{{ route('admin_submit_reservation') }}" method="POST" class="col s12" id="adminConfirmReservationForm">
                                @csrf
                                <div class="row">
                                    <div class="input-field col s12 l8">
                                        <input type="text" disabled value="Admin Ticket">
                                        <label >Ticket</label>
                                    </div>
                                    <div class="input-field col s12 l4">
                                        <input type="text" name="desk" readonly value=" " id="deskInputDisplay">
                                        <label for="deskInputDisplay">Desk</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s12">
                                        <input type="text" name="user_name" readonly value=" " id="userNameConfirm">
                                        <label for="userNameConfirm">User</label>
                                    </div>
                                </div>
                               
                                <div class="row">
                                    <div class="input-field col s12">
                                        <p class="flow-text" id="remarkDisplay"></p>
                                        <label for="remarkDisplay">Remark</label>
                                    </div>
                                </div>
                                <input type="hidden" name="user_id" id="userIdConfirm">
                                <input type="hidden" name="date" id="dateConfirm">
                                <input type="hidden" name="time" id="timeConfirm">
                                <input type="hidden" name="duration" id="durationConfirm">
                                <input type="hidden" name="type" id="typeConfirm">
                                <input type="hidden" name="desk_id" id="deskInput">
                                <input type="hidden" name="request_notes" id="remarkConfirm">
                                <div class="row">
                                    <div class="input-field col s12">
                                        <button class="btn right green waves-effect btn-block btn-large">
                                            Create Ticket & Start Reservation<i class="material-icons right">save</i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <div class="clearfix"></div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col s12 m12 l7">
        <ul class="collapsible"  id="bookingDesksCollapsible">
            <li @if (!$agent->isMobile()) class="active" @endif>
                <div class="collapsible-header gradient-45deg-indigo-light-blue white-text">Desk/Room</div>
                <div class="collapsible-body white px-0 mx-0" style="overflow-x: hidden">
                    <div id="desksContainer">
                                
                    </div>
                    <div class="clearfix"></div>
                    
                </div>
            </li>
        </ul>
    </div>
</div>
@endsection

