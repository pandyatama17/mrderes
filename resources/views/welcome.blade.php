@extends('layouts.pagewrapper')
@section('page')
    <div class="col s12 m12 l12">
        <div class="card" style="border-radius: 10px;">
            <div class="card-content gradient-45deg-deep-orange-orange" style="border-top-left-radius: 10px; border-top-right-radius:10px;">
                <span class="card-title white-text"> Zone Red</span>
            </div>
            <div class="card-content">
                @for ($i = 1; $i < 40; $i++)
                    @php
                        $color=array("red","green");
                        $random = $color[array_rand($color, 1)];
                    @endphp
                    <div class="col s2 m1 l1 desk-container center-align hoverable trigger-sidenav" data-target="slide-out-right">
                        <a href="#" data-zone="red" data-desk="D{{$i}}"  class="black-text select-desk"><i class="material-icons">computer</i></a>
                        <br>
                        <p class="{{$random}} white-text center-align"><a href="#" data-zone="red" data-desk="R{{$i}}" class="white-text select-desk">R{{$i}}</a></p>
                        <div class="clearfix"></div>
                    </div>
                @endfor
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
@endsection