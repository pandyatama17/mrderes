@extends('layouts.pagewrapper')
@section('page')
<link rel="stylesheet" href="{{ asset('app-assets/css/pages/data-tables.css"') }}">
{{-- <link rel="stylesheet" href="{{ asset('app-assets/js/scripts/data-tables.js') }}"> --}}
    <div class="row white p-5 border-radius-6">
        <div class="col s12">
            <table class="table striped datatable bordered">
                <thead>
                    <tr class="grey lighten-2">
                        <th>ID</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Parent Room</th>
                        <th>Availability</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($desks as $desk)
                        @php
                            switch ($desk->zone_id) {
                                case 1:
                                    $class = 'gradient-45deg-indigo-purple white-text';
                                    break;
                                case 2:
                                    if (!$desk->parent_id) 
                                    {
                                        $class = 'gradient-45deg-amber-amber black-text';
                                    }
                                    else {
                                        $class = 'gradient-45deg-purple-deep-purple white-text';
                                    }
                                    break;
                                default:
                                    $class = 'grey lighten-3';
                                    break;
                            }
                        @endphp
                        <tr >
                            <td>{{ $desk->id }}</td>
                            <td class="{{ $class }}">{{ $desk->desk_name }}</td>
                            <td><span class="badge border-radius-6 {{ $class }}">{{ $desk->zone }}</span></td>
                            <td><span class="{{ ($desk->parent ? 'gradient-45deg-amber-amber black-text' : '') }}">{{ ($desk->parent ? $desk->parent : '-') }}</span></td>
                            <td>{!! ($desk->availability ? '<span class="badge green border-radius-6">Enabled</span>' : '<span class="badge grey border-radius-6">Disabled</span>') !!}</td>
                            <td>
                                @if ($desk->availability)
                                    <a href="#" class="waves-effect red-text">
                                        <i class="material-icons left">lock</i> | Disable
                                    </a>
                                @else
                                    <a href="#" class="waves-effect green-text">
                                        <i class="material-icons left">lock_open</i> | Enable
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection