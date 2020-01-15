@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                     <div class="row">
                        <div class="col-md-2">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>{{$user->firstname}} {{$user->lastname}}</strong>
                        </div>
                        <div class="col-md-6">
                            <div class="row">
                                @if($user->email != 'admin@admin.com')

                                <div class="col-md-4">
                                        <strong>Available : {{$user->availability ? 'Yes' : 'No'}}</strong>
                                    </div>
                                    <div class="col-md-4">
                                       {{-- @if($user->availability)
                                            <a href="{{route('notavailable', $user->id)}}" class="btn btn-sm btn-warning">Make Unavailable</a>
                                        @else
                                            <a href="{{route('available', $user->id)}}" class="btn btn-sm btn-primary">Make Available</a>
                                        @endif--}}
                                    </div>

                                @endif

                                <div class="col-md-4">
                                    <a href="{{route('users.edit', $user->id)}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-pencil"></i> Edit Profile</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div></div>

                <div class="">

                    <div class="row">
                            <div class="col-md-3">

                              <!-- Profile Image -->
                              <div class="box box-success">
                                <div class="box-body box-profile">
                                  {{-- <img class="profile-user-img img-responsive img-circle" src="{{asset('img/user.png')}}" alt="User profile picture"> --}}
                                  <img class="profile-user-img img-responsive img-circle" src="{{asset('img/user.png')}}" alt="{{$user->name}}'s profile picture">

                                  <h3 class="profile-username text-center">{{$user->name}}</h3>

                                  <p class="text-muted text-center">{{$user->role->name ?? 'No Role'}}</p>

                                  <ul class="list-group list-group-unbordered">

                                     <li class="list-group-item"><strong>Phone : </strong><a>{{$user->phone}}</a></li>
                                     <li class="list-group-item"><strong>Address : </strong><a>{{$user->address}}</a></li>
                                     <li class="list-group-item"><strong>Role : </strong><a>{{App\Role::find($user->role_id)->name ?? 'No Role'}}</a></li>
                                     <li class="list-group-item"><strong>Email : </strong><a>{{$user->email}}</a></li>
                                     <li class="list-group-item"><strong>Created On : </strong><a>{{$user->created_at->format('D jS, M, Y')}}</a></li>

                                  </ul>

                                  {{-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> --}}
                                </div>
                                <!-- /.box-body -->
                              </div>
                              <!-- /.box -->


                              <!-- /.box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-md-9">

                              <div class="nav-tabs-custom">

                                <ul class="nav nav-tabs nav-tabs-success">
                                  <li class="active"><a href="#more_details" data-toggle="tab">More Details</a></li>
                                  {{-- <li><a href="#patients" data-toggle="tab">Patients</a></li> --}}
                                  @if(auth()->user()->role_id != 6)
                                  <li><a href="#appointments" data-toggle="tab">Appointments</a></li>
                                  @endif
                                  @if(!auth()->user()->role_id == 5)
                                    <li><a href="#tasks" data-toggle="tab">Tasks</a></li>
                                  @endif
                                  @if(auth()->user()->role_id != 6)
                                  <li><a href="#invoices" data-toggle="tab">Invoices</a></li>
                                  @endif
                                </ul>

                                <div class="tab-content">

                                  <div class="active tab-pane" id="more_details">
                                    <div class="panel panel-success">
                                      <div class="panel-heading">
                                         More Information
                                      </div>
                                      <div class="panel-body">
                                          {!! $user->more_info ?? 'No Information' !!}
                                      </div>
                                    </div>
                                    <h4></h4>
                                  </div>

                                  @if(!auth()->user()->role_id == 5)

                                  {{-- <div class="tab-pane" id="patients">

                                        <h4>User's Patients</h4>
                                        @if (count($user->patients) > 0)
                                           <table class="table table-bordered" id="patients_table">
                                                <thead>
                                                    <tr>
                                                        <th>NHS Number</th>
                                                        <th>First Name</th>
                                                        <th>Last Name</th>
                                                        <th>Created On</th>
                                                    </tr>

                                                </thead>
                                                <tbody>
                                                 @foreach ($user->patients as $patient)
                                                    <tr>
                                                        <td><a href="{{route('patients.show', $patient->id)}}">{{$patient->nhs_number}}</a></td>
                                                        <td>{{$patient->firstname}}</td>
                                                        <td>{{$patient->lastname}}</td>
                                                        <td>{{$patient->created_at->diffForHumans()}}</td>
                                                    </tr>
                                                  @endforeach
                                                  </tbody>
                                               </table>
                                              @else
                                                  User has No Patients yet.
                                              @endif

                                  </div> --}}

                                  @endif

                                  <!-- /.tab-pane -->
                                  <div class="tab-pane" id="appointments">
                                        <h4>User's Appointments</h4>
                                        @if (count($user->appointments) > 0)
                                            <table class="table table-bordered" id="appointments_table">
                                                <thead>
                                                    <th>Name</th>
                                                    <th>Start Date</th>
                                                    {{-- <th>End Date</th> --}}
                                                    <th>Status</th>
                                                    <th>Doctor Assigned</th>
                                                    <th>Created On</th>
                                                </thead>
                                                <tbody>
                                                    @foreach ($user->appointments as $appointment)
                                                        <tr>
                                                            <td><a href="{{route('appointments.show', $appointment->id)}}"> {{$appointment->service->name}}</a></td>
                                                            <td>{{date('D, jS, M, Y', strtotime($appointment->appointment_date))}}</td>
                                                            {{-- <td>{{$appointment->end_date}}</td> --}}
                                                            <td>{{$appointment->status}}</td>
                                                            <td>
                                                                {{App\User::findOrfail($appointment->doctor_id)->firstname}}
                                                                {{App\User::findOrfail($appointment->doctor_id)->lastname}}
                                                            </td>
                                                            <td>{{$appointment->created_at->diffForHumans()}}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            user has No Appointments yet.
                                        @endif
                                  </div>

                                  @if(!auth()->user()->role_id == 5)
                                  <div class="tab-pane" id="tasks">
                                        <h4>User's Tasks</h4>
                                        @if (count($user->tasks) > 0)
                                            <table class="table table-bordered" id="tasks_table">
                                                <thead>
                                                    <th>#ID</th>
                                                    <th>Name</th>
                                                    <th>Deadline</th>
                                                    <th>Status</th>
                                                    <th>Doctor Assigned</th>
                                                    <th>Created On</th>
                                                </thead>
                                                <tbody>
                                                    @foreach ($user->tasks as $task)
                                                        <tr>
                                                            <td>{{$task->id}}</td>
                                                            <td><a href="{{route('tasks.show', $task->id)}}">{{$task->name}}</a></td>
                                                            <td>{{$task->deadline}}</td>
                                                            <td>{{$task->status}}</td>
                                                            <td>{{App\User::findOrfail($task->user_id)->name}}</td>
                                                            <td>{{$task->created_at->diffForHumans()}}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            user has No tasks yet.
                                        @endif
                                  </div>
                                  @endif

                                  <div class="tab-pane" id="invoices">
                                        <h4>user's Invoices</h4>
                                        @if (count($user->invoices) > 0)
                                            <table class="table table-bordered" id="invoices_table">
                                                <thead>
                                                    <th>Invoice No.</th>
                                                    <th>Product / Service</th>
                                                    <th>Amount</th>
                                                    <th>Due date</th>
                                                    <th>Doctor Assigned</th>
                                                    <th>Created On</th>
                                                </thead>
                                                <tbody>
                                                    @foreach ($user->invoices as $invoice)
                                                        <tr>
                                                            <td><a href="{{route('invoices.show', $invoice->id)}}">#00HN - {{$invoice->id}}</a></td>
                                                            <td>{{$invoice->product_service}}</td>
                                                            <td>{{$invoice->amount}}</td>
                                                            <td>{{$invoice->due_date}}</td>
                                                            <td>{{App\User::findOrfail($invoice->user_id)->name}}</td>
                                                            <td>{{$invoice->created_at->diffForHumans()}}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @else
                                            user has No invoices yet.
                                        @endif
                                  </div>

                                  <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->
                              </div>
                              <!-- /.nav-tabs-custom -->
                            </div>
                            <!-- /.col -->
                          </div>


                        </div>

@endsection
