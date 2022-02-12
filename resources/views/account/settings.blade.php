@extends('layouts.pagewrapper')
@section('page')
<link rel="stylesheet" type="text/css" href="{{ asset('app-assets/css/pages/page-account-settings.css') }}">
<section class="tabs-vertical mt-1 section">
    <div class="row">
      <div class="col l4 s12">
        <!-- tabs  -->
        <div class="card-panel">
          <ul class="tabs">
            <li class="tab">
              <a href="#general">
                <i class="material-icons">brightness_low</i>
                <span>General</span>
              </a>
            </li>
            <li class="tab">
              <a href="#change-password">
                <i class="material-icons">lock_open</i>
                <span>Change Password</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
      <div class="col l8 s12">
        <!-- tabs content -->
        <div id="general">
          <div class="card-panel">
            <form class="formValidate" method="POST" action="{{ route('account_update') }}" id="generalForm">
              @csrf
              <div class="row">
                <div class="col s12">
                  <div class="input-field">
                    <label for="name">Name</label>
                    <input id="name" name="name" type="text" value="{{ Auth::user()->name }}" readonly data-error=".errorTxt2">
                    <small class="errorTxt2"></small>
                  </div>
                </div>
                <div class="col s12">
                  <div class="input-field">
                    <label for="email">E-mail</label>
                    <input id="email" type="email" name="email" value="{{ Auth::user()->email }}" readonly data-error=".errorTxt3">
                    <small class="errorTxt3"></small>
                  </div>
                </div>
                @if (!Auth::user()->email_verified_at)
                  <div class="col s12">
                    <div class="card-alert card orange lighten-5">
                      <div class="card-content orange-text">
                        <i class="material-icons left">lock</i>
                        <p>Your account is suspended. Please contact your administrator to use this account</p>
                      </div>
                    </div>
                  </div>
                @else
                  <div class="col s12">
                    <div class="card-alert card green lighten-5">
                      <div class="card-content green-text">
                        <i class="material-icons left">lock_open</i>
                        <p>Your account status is available. You may use this account to make reservations</p>
                      </div>
                    </div>
                  </div>
                @endif
                <div class="col s12">
                  <div class="input-field">
                    <i class="prefix"><sup>+62</sup></i>
                    <input id="phone" name="phone" type="text" value="{{ Auth::user()->phone }}">
                    <label for="phone">Phone</label>
                  </div>
                </div>
                <div class="col s12 display-flex justify-content-end form-action">
                  <button type="submit" class="btn indigo waves-effect waves-light mr-2">
                    Save changes
                  </button>
                </div>
              </div>
            </form>
          </div>
        </div>
        <div id="change-password">
          <div class="card-panel">
            <form class="paaswordvalidate" method="POST" action="{{ route('account_change_password') }}" id="passwordForm">
              @csrf
              <div class="row">
                <div class="col s12">
                  <div class="input-field">
                    <input id="oldpswd" name="oldpswd" type="password" data-error=".errorTxt4">
                    <label for="oldpswd">Old Password</label>
                    <small class="errorTxt4"></small>
                  </div>
                </div>
                <div class="col s12">
                  <div class="input-field">
                    <input id="newpswd" name="newpswd" type="password" data-error=".errorTxt5">
                    <label for="newpswd">New Password</label>
                    <small class="errorTxt5"></small>
                  </div>
                </div>
                <div class="col s12">
                  <div class="input-field">
                    <input id="repswd" type="password" name="repswd" data-error=".errorTxt6">
                    <label for="repswd">Retype new Password</label>
                    <small class="errorTxt6"></small>
                  </div>
                </div>
                <div class="col s12 display-flex justify-content-end form-action">
                  <button type="submit" class="btn indigo waves-effect waves-light mr-1">Save changes</button>
                  <button type="reset" class="btn btn-light-pink waves-effect waves-light">Cancel</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection