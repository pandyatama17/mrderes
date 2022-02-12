<aside class="sidenav-main nav-expanded nav-lock nav-collapsible sidenav-light sidenav-active-square">
    <div class="brand-sidebar">
      <h1 class="logo-wrapper">
        <a class="brand-logo darken-1" href="{{ route('index') }}">
          <img class="hide-on-med-and-down" src="{{asset('images/logo.png')}}" alt="logo"/>
          <img class="show-on-medium-and-down hide-on-med-and-up" src="{{asset('/images/logo.png')}}" alt=" logo"/>
          <span class="logo-text hide-on-med-and-down" style="font-size:15pt">Booking System</span>
        </a>
        <a class="navbar-toggler" href="#"><i class="material-icons">radio_button_checked</i></a>
      </h1>
    </div>
    <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out" data-menu="menu-navigation" data-collapsible="menu-accordion">
      <li class="navigation-header"><a class="navigation-header-text">Reservation</a><i class="navigation-header-icon material-icons">more_horiz</i></li>
      @if (Auth::user() && Auth::user()->role != 'ADMIN')
        @if (Auth::user()->email_verified_at)
        <li class="bold"><a class="waves-effect waves-cyan router-link" data-page="ticket" href="{{route('ticket')}}"><i class="material-icons">confirmation_number</i><span class="menu-title" data-i18n="Mail">Ticket</span>{{--<span class="badge new badge pill pink accent-2 float-right mr-2">5</span>--}}</a></li>
        <li class="bold"><a class="waves-effect waves-cyan router-link" data-page="booking" href="{{route('booking')}}"><i class="material-icons">event_seat</i><span class="menu-title" data-i18n="Mail">Booking</span>{{--<span class="badge new badge pill pink accent-2 float-right mr-2">5</span>--}}</a></li>             
        @endif
      @else
        <li class="bold"><a class="waves-effect waves-cyan router-link" data-page="booking" href="{{route('create_reservation_admin')}}"><i class="material-icons">addchart</i><span class="menu-title" data-i18n="Mail">Create Reservation</span>{{--<span class="badge new badge pill pink accent-2 float-right mr-2">5</span>--}}</a></li>             
      @endif
      <li class="bold"><a class="collapsible-header waves-effect waves-cyan " href="JavaScript:void(0)"><i class="material-icons">today</i><span class="menu-title" data-i18n="Pages">Calendar</span></a>
        <div class="collapsible-body">
          <ul class="collapsible collapsible-sub" data-collapsible="accordion">
            <li class="bold"><a class="waves-effect waves-cyan " href="{{route('calendar')}}"><i class="material-icons">desktop_mac</i><span class="menu-title" data-i18n="Calendar">Hot Desk</span></a></li>
            <li class="bold"><a class="waves-effect waves-cyan " href="{{route('calendar_mr')}}"><i class="material-icons">meeting_room</i><span class="menu-title" data-i18n="Calendar">Meeting Room</span></a></li>
          </ul>
        </div>
      </li>
      @if (Auth::user() && Auth::user()->role != 'ADMIN')
        <li class="bold"><a class="waves-effect waves-cyan router-link" data-page="requests" href="{{route('requests')}}"><i class="material-icons">receipt_long</i><span class="menu-title">My Reservations</span></a></li>
      @endif
      @if (Auth::user() && Auth::user()->role != "STAFF" )
        <li class="navigation-header"><a class="navigation-header-text">Approver Area </a><i class="navigation-header-icon material-icons">more_horiz</i></li>
        <li class="bold"><a class="waves-effect waves-cyan " href="{{ route('ticket_requests') }}"><i class="material-icons">assignment</i><span class="menu-title" data-i18n="Ticket Requests">Ticket Requests</span></a></li>
        @if (Auth::user()->role != "ADMIN")
          <li class="bold"><a class="waves-effect waves-cyan " href="{{ route('show_team') }}"><i class="material-icons">groups</i><span class="menu-title" data-i18n="User Profile">Team</span></a></li>
        @endif
      @endif
      <li class="navigation-header"><a class="navigation-header-text">User Area  </a><i class="navigation-header-icon material-icons">more_horiz</i></li>
      <li><a href="{{ route('account_settings') }}"><i class="material-icons">settings</i><span data-i18n="Search">Account Settings</span></a></li>
      @if (Auth::user() && Auth::user()->id == 1)
        <li class="navigation-header"><a class="navigation-header-text">Admin Area </a><i class="navigation-header-icon material-icons">more_horiz</i></li>
        <li class="bold"><a class="waves-effect waves-cyan " href="{{ route('show_users') }}"><i class="material-icons">people</i><span class="menu-title" data-i18n="User Profile">Users</span></a></li>
        {{-- <li class="bold"><a class="waves-effect waves-cyan " href="{{ route('show_desks') }}"><i class="material-icons">room_preferences</i><span class="menu-title" data-i18n="User Profile">Desks & Rooms</span></a></li> --}}
      @endif
    </ul>
    <div class="navigation-background"></div><a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium red waves-effect waves-light hide-on-large-only" href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
  </aside>