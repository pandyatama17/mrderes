<div class="app-wrapper @if($agent->isMobile()) pt-5 mt-5 @endif">
    <div class="datatable-search">
        <i class="material-icons mr-2 search-icon">search</i>
        <input type="text" placeholder="Search Users" class="app-filter" id="global_filter">
    </div>
    <div id="button-trigger" class="card card card-default scrollspy border-radius-6 fixed-width">
        <div class="card-content p-0">
            <table id="data-table-contact" class="display">
                <thead>
                    <tr>
                        <th class="all">Name</th>
                        <th class="all">Email</th>
                        <th class="all">Phone</th>
                        <th class="none">Role</th>
                        <th class="none">User Status</th>
                        <th class="none">Privileges</th>
                        <th class="none">Approver</th>
                        <th class="none">CC Approver</th>
                        <th class="all">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->phone }}</td>
                            <td>
                                @if ($user->role == 'TL')
                                    Approver
                                @elseif($user->role == 'Head Officer')
                                    CC Approver
                                @else
                                    Staff
                                @endif
                            </td>
                            <td>
                                @if ($user->email_verified_at)
                                    <span class="badge green border-radius-6">Active</span>
                                @else
                                    <span class="badge orange border-radius-6">Suspended</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $roles = \App\Models\ReservationPrivilege::where('user_id',$user->id)->get();
                                @endphp
                                @foreach ($roles as $role)
                                    @if ($role->zone_id == 1)
                                        <span class="badge purple border-radius-6">Hot Desk</span>
                                    @else
                                        <span class="badge amber border-radius-6">Meeting Room</span>
                                    @endif
                                @endforeach
                            </td>
                            
                            <td>{{ $user->approver_id ? $user->approver : "-" }}</td>
                            <td>{{ $user->cc_id ? $user->cc : "-" }}</td>
                            <td>
                                <a class="light-blue-text text-darken-4 left editUserTrigger"
                                    href="{{ $user->id }}"
                                    data-id= "{{ $user->id }}"
                                    data-approver_id= "{{ $user->approver_id }}"
                                    data-cc_id= "{{ $user->cc_id }}"
                                    data-division_id= "{{ $user->division_id }}"
                                    data-nik= "{{ $user->nik }}"
                                    data-name= "{{ $user->name }}"
                                    data-email= "{{ $user->email }}"
                                    data-role= "{{ $user->role }}"
                                    data-phone= "{{ $user->phone }}"
                                >
                                    <i class="material-icons left light-blue-text text-darken-4">edit</i>Edit Information
                                </a>
                                <br>
                                <a class="{{ ($user->email_verified_at ? "orange-text" : "green-text") }} text-darken-4 left toggleUserStatusTrigger"
                                    data-id="{{ $user->id }}"
                                    data-name="{{ $user->name }}"
                                >
                                    @if ($user->email_verified_at)
                                        <i class="material-icons left orange-text text-darken-4">lock</i>Suspend User
                                    @else
                                        <i class="material-icons left green-text text-darken-4">lock_open</i>Enable User
                                    @endif
                                </a>
                            </td>
                            {{-- <td>{{ $user->approver_id ? \App\Models\User::find($user->approver_id)->name : "-" }}</td>
                            <td>{{ $user->cc_id ? \App\Models\User::find($user->cc_id)->name : "-" }}</td> --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>