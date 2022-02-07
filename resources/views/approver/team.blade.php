@extends('layouts.pagewrapper')
@section('page')
<div class="col s12 m12 l12">
    <br>
    <div class="card center">
        <div class="card-content">
            <p class="caption ">My Team members</p>
        </div>
    </div>
    <div class="row">
       <div class="col s12">
        <table class="table striped datatable bordered">
                <thead>
                    <th>Name</th>
                    <th>NIK</th>
                    <th>e-mail</th>
                    <th>Role</th>
                    <th>Phone</th>
                </thead>
                <tbody>
                    @foreach ($team as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->nik }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role }}</td>
                            <td>{{ $user->phone }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
       </div>
    </div>
</div>
@endsection