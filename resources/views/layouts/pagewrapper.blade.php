@extends('layouts.header')
@section('title',$crumbs['page'])
@section('content')
<div id="main">
    <div class="row">
        <div class="content-wrapper-before gradient-45deg-red"></div>
        <div class="breadcrumbs-dark pb-0 pt-4" id="breadcrumbs-wrapper">
            <div class="container">
                <div class="row">
                    @if (isset($crumbs))
                        <div class="col s10 m6 l6">
                            <h5 class="breadcrumbs-title mt-0 mb-0" id="crumbs-page"><span>{{$crumbs['page']}}</span></h5>
                            <ol class="breadcrumbs mb-0" id="crumbs">
                                @foreach ($crumbs['pages'] as $crumb)
                                    <li class="breadcrumb-item"><a href="{{$crumb['url']}}">{{$crumb['title']}}</a></li>
                                @endforeach
                                <li class="breadcrumb-item active">{{$crumbs['page']}}</li>
                            </ol>
                        </div>
                    @else
                        <div class="col s10 m6 l6">
                            <h5 class="breadcrumbs-title mt-0 mb-0" id="crumbs-page"><span>Blank Page</span></h5>
                            <ol class="breadcrumbs mb-0" id="crumbs">
                                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                                <li class="breadcrumb-item"><a href="#">Pages</a></li>
                                <li class="breadcrumb-item active">Blank Page</li>
                            </ol>
                        </div>
                    @endif
                    {{-- <div class="col s2 m6 l6">
                        <a class="btn dropdown-settings waves-effect waves-light breadcrumbs-btn right" href="#!" data-target="dropdown1"><i class="material-icons hide-on-med-and-up">settings</i><span class="hide-on-small-onl">Settings</span><i class="material-icons right">arrow_drop_down</i></a>
                        <ul class="dropdown-content" id="dropdown1" tabindex="0">
                            <li tabindex="0"><a class="grey-text text-darken-2" href="user-profile-page.html">Profile<span class="new badge red">2</span></a></li>
                            <li tabindex="0"><a class="grey-text text-darken-2" href="app-contacts.html">Contacts</a></li>
                            <li tabindex="0"><a class="grey-text text-darken-2" href="page-faq.html">FAQ</a></li>
                            <li class="divider" tabindex="-1"></li>
                            <li tabindex="0"><a class="grey-text text-darken-2" href="user-login.html">Logout</a></li>
                        </ul>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="col s12">
            <div class="container">
                <input type="hidden" id="current-page">
                <div class="section" id="main-content">
                    @yield('page')
                </div>
                <!-- START RIGHT SIDEBAR NAV -->
                {{-- @include('layouts.rightnav') --}}
                <!-- END RIGHT SIDEBAR NAV -->
                {{-- <div style="bottom: 50px; right: 19px;" class="fixed-action-btn direction-top"><a class="btn-floating btn-large gradient-45deg-light-blue-cyan gradient-shadow"><i class="material-icons">confirmation_number</i></a>
                    <ul>
                        <li><a href="css-helpers.html" class="btn-floating blue"><i class="material-icons">help_outline</i></a></li>
                        <li><a href="cards-extended.html" class="btn-floating green"><i class="material-icons">widgets</i></a></li>
                        <li><a href="app-calendar.html" class="btn-floating amber"><i class="material-icons">today</i></a></li>
                        <li><a class="btn-floating red sidenav-trigger" id="sidenavTrigger" href="#" data-target="slide-out-right"><i class="material-icons">event_seat</i></a></li>
                    </ul>
                </div> --}}
            </div>
            <div class="content-overlay"></div>
        </div>
    </div>
</div>
@endsection