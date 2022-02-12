<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
  <head>
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>MRDERES</title>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons"rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/vendors.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/themes/vertical-modern-menu-template/materialize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/themes/vertical-modern-menu-template/style.css')}}">
    
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/materialize-stepper/materialize-stepper.min.css')}}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.3/dist/sweetalert2.min.css">
    <link rel="stylesheet" type="text/css" href="{{asset('main.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('spacing.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('MDTimePicker/mdtimepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/select2/select2.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/vendors/select2/select2-materialize.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.3.4/sweetalert2.css" integrity="sha512-fSWkjL6trYj6KvdwIga30e8V4h9dgeLxTF2q2waiwwafEXD+GXo5LmPw7jmrSDqRun9gW5KBR+DjvWD+5TVr8A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/data-tables/css/jquery.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('app-assets/vendors/data-tables/extensions/responsive/css/responsive.dataTables.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('app-assets/css/custom/custom.css')}}">
    <link rel="stylesheet" href="{{ asset('app-assets/css/pages/app-contacts.css') }}">
    <script src="{{asset('app-assets/js/vendors.min.js')}}"></script>
    <script src="{{asset('imagemapster.min.js')}}"></script>
    <script>
        var assetsUrl = '{{ asset("/") }}';
        var isMobile = @if($agent->isMobile()) 1 @else 0 @endif;
    </script>
  </head>
  <div class="main-loader">
  <div class="preloader-wrapper big active">
    <div class="spinner-layer spinner-blue-only">
      <div class="circle-clipper left">
        <div class="circle"></div>
      </div>
      <div class="gap-patch">
        <div class="circle"></div>
      </div>
      <div class="circle-clipper right">
        <div class="circle"></div>
      </div>
    </div>
  </div>
</div>

  <body class="vertical-layout vertical-menu-collapsible page-header-dark vertical-modern-menu preload-transitions 2-columns   " data-open="click" data-menu="vertical-modern-menu" data-col="2-columns">
    <header class="page-topbar" id="header">
      <div class="navbar navbar-fixed"> 
        <nav class="navbar-main navbar-color nav-collapsible sideNav-lock navbar-dark gradient-45deg-red no-shadow">
          <div class="nav-wrapper">
            <ul class="navbar-list right">
              {{-- <li><a class="waves-effect waves-block waves-light toggle-fullscreen" href="javascript:void(0);"><i class="material-icons">settings_overscan</i></a></li> --}}
              {{-- <li>
                <a class="waves-effect waves-block waves-light notification-button" href="javascript:void(0);" data-target="notifications-dropdown">
                  <i class="material-icons">widgets
                  </i>
                </a>
              </li> --}}
              <li>
                <a class="waves-effect waves-block waves-light profile-button" href="javascript:void(0);" data-target="profile-dropdown">
                  <span class="avatar-status avatar-online"><img src="{{ asset('images/user.png') }}" alt="avatar"><i></i></span>
                </a>
              </li>
            </ul>
            {{-- <ul class="dropdown-content" id="notifications-dropdown">
              <li>
                <h6>
                  Quick Menu
                </h6>
              </li>
              <li class="divider"></li>
              <li>
                <a class="black-text" href="#!">
                  <span class="material-icons icon-bg-circle cyan small">confirmation_number</span> Latest Ticket 
                </a>
                @if (Auth::user() && Auth::user()->role != 'ADMIN')
                  @php
                      $nticket = \App\Models\Ticket::where('user_id',Auth::user()->id)->orderBy('datetime','DESC')->first();
                  @endphp
                  @if ($nticket)
                    <time class="media-meta grey-text darken-2 right" datetime="{{ \Carbon\Carbon::now() }}" style="line-height: 110%">
                      @switch($nticket->type)
                          @case('MR')
                              <span class="amber-text">Meeting Room</span>
                              @break
                          @case('D')
                            <span class="purple-text">Hot Desk</span>
                            @break
                      @endswitch
                      , {{ \Carbon\Carbon::parse($nticket->datetime)->format('D, d F Y') }} 
                      &#64;{{ \Carbon\Carbon::parse($nticket->datetime)->format('g:i A') }} - {{ \Carbon\Carbon::parse($nticket->datetime)->addHours($nticket->duration)->format('g:i A') }}. 
                      @switch($nticket->status)
                          @case(0)
                              <span class="red-text">Pending Approval</span>
                              @break
                          @case(1)
                            <span class="green-text">Approved, available to use</span>
                            @break
                          @case(2)
                            <span class="amber-text">Used</span>
                            @break
                          @default
                              
                      @endswitch
                    </time>
                  @endif                    
                @endif
              </li>
              @if (Auth::user() && Auth::user()->role != 'ADMIN')
                  @php
                      $nckin = \App\Models\Reservation::where('user_id',Auth::user()->id)
                                ->where('datetime_end', '<',\Carbon\Carbon::now() )
                                ->whereNull('start_time')
                                ->orderBy('datetime_start','DESC')->first();
                      if($nckin)
                      {
                        $desk = \App\Models\Desk::find($nckin->desk_id);
                      }
                  @endphp
                  @if ($nckin && \Carbon\Carbon::now()->gte(\Carbon\Carbon::parse($nckin->datetime_start)))
                    <li>
                      <a class="black-text" href="#!"><span class="material-icons icon-bg-circle teal small">assignment_turned_in</span> Check In</a>
                      <time class="media-meta grey-text darken-2" datetime="{{ \Carbon\Carbon::now() }}">
                        @if ($desk->zone_id == 1)
                            <span class="purple-text">{{ $desk->desk_name }}</span>
                        @else
                          <span class="amber-text">{{ $desk->desk_name }}</span>
                        @endif
                        for {{ \Carbon\Carbon::parse($nckin->datetime_start)->format('l, d F Y') }} &#64;{{ \Carbon\Carbon::parse($nckin->datetime_start)->format('g:i A') }} - {{ \Carbon\Carbon::parse($nckin->datetime_end)->format('g:i A') }}
                      </time>
                    </li>  
                  @endif                    
              @endif
            </ul> --}}
            <ul class="dropdown-content" id="profile-dropdown">
              @if (Auth::user())
              <li><a href="#" class="grey-text text-darken-1">{{ Auth::user()->name }}</a></li>
              <li class="divider"></li>
              <li><a class="grey-text text-darken-1" href="{{ route('account_settings') }}"><i class="material-icons">settings</i> Account</a></li>
              <li>
                <a class="grey-text text-darken-1"  href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="material-icons">keyboard_tab</i> Logout
                  </a>
                  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
              </li>
                @else
                <li><a class="grey-text text-darken-1" href="{{ route('login') }}"><i class="material-icons">login</i> Login</a></li>
              @endif
            </ul>
          </div>
          
        </nav>
      </div>
    </header>
    @include('layouts.leftnav')
    @yield('content')
    <footer class="page-footer footer footer-static footer-dark gradient-45deg-indigo-purple gradient-shadow navbar-border navbar-shadow">
      <div class="footer-copyright">
        <div class="container"><span>&copy; 2022          KIMOCHIINSIDE All rights reserved.</span></div>
      </div>
    </footer>
    <script src="{{asset('app-assets/vendors/materialize-stepper/materialize-stepper.min.js')}}"></script>
    <script src="{{asset('app-assets/js/plugins.js')}}"></script>
    <script src="{{asset('app-assets/js/search.js')}}"></script>
    <script src="{{asset('app-assets/js/custom/custom-script.js')}}"></script>
    <script src="{{asset('app-assets/js/scripts/customizer.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js" integrity="sha512-qTXRIMyZIFb8iQcfjXWCO8+M5Tbc38Qi5WzdPOYZHIlZpzBHG3L3by84BBBOiRGiEb7KKtAOAs5qYdUiZiQNNQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.3.3/dist/sweetalert2.all.js"></script>
    <script src="{{asset('MDTimePicker/mdtimepicker.js')}}"></script>
    <script src="{{ asset('app-assets/vendors/select2/select2.full.min.js') }}"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{asset('demo.js')}}"></script>
    <script src="{{asset('mapster.js')}}"></script>

    <script src="{{ asset('app-assets/vendors/data-tables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('app-assets/vendors/data-tables/extensions/responsive/js/dataTables.responsive.min.js') }}"></script>
    
    <script src="{{ asset('app-assets/js/scripts/app-contacts.js') }}"></script>
   @if (Session::has('message'))
        <script>
          Swal.fire('{{ Session::get("message")["title"] }}','{{ Session::get("message")["body"] }}','{{ Session::get("message")["type"] }}');
        </script>
    @endif
    @if (Session::has('page'))
        <script>
           setTimeout(
            function() 
            {
                redir_route('{{ Session::get('page') }}');
            }, 1000);
        </script>
    @endif
  </body>
</html>