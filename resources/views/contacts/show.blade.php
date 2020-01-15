@extends('adminlte::page')

@section('content')

    <div class="panel panel-success">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-4">
                    <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back
                    </button>
                </div>
                <div class="col-md-4">
                    <strong>{{$contact->relative_name}}</strong>
                </div>
                <div class="col-md-4">
                    <a href="{{route('contacts.edit', $contact->id)}}" class="btn btn-sm btn-primary btn-block"><i
                                class="fa fa-pencil"></i> Edit Client</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3">
            <!-- Profile Image -->
            <div class="box box-success">
                <div class="box-body box-profile">

                    @if($contact->profile_photo)
                        <img class="profile-user-img img-responsive img-circle"
                              src="{{asset('/storage/'.$contact->profile_photo)}}"
                              alt="{{$contact->firstname}} {{$contact->lastname}}'s' profile picture">
                    @else
                        <img class="profile-user-img img-responsive img-circle" src="{{asset('img/user.png')}}"
                              alt="{{$contact->firstname}} {{$contact->lastname}}'s' profile picture">
                    @endif

                    <h3 class="profile-contactname text-center">{{$contact->relative_name}}</h3>

                    <ul class="list-group list-group-unbordered">

                        <li class="list-group-item"><strong>Date of Birth : </strong><a
                                    class="pull-right">{{$contact->date_of_birth->format('D, M, Y')}}</a></li>
                        <li class="list-group-item"><strong>NHS Number : </strong><a
                                    class="pull-right">{{$contact->nhs_number ?? 'N/A'}}</a></li>
                        <li class="list-group-item"><strong>Phone : </strong><a
                                    class="pull-right">{{$contact->phone}}</a></li>
                        <li class="list-group-item"><strong>Email : </strong><a
                                    class="pull-right">{{$contact->email}}</a></li>
                    </ul>
                    {{-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> --}}
                </div>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
        <!-- /.col -->
        <div class="col-md-9">
            @include('inc.tabMenu', ['tabMenuPosition'=>2, 'patient_id'=>$contact->user_id])
            <div class="nav-tabs-custom">

                <ul class="nav nav-tabs nav-tabs-success">
                    <li class="active"><a href="#more_details" data-toggle="tab">More Details</a></li>
                    <li><a href="#med_history" data-toggle="tab">Medical History</a></li>
                </ul>

                <div class="tab-content">

                    <div class="active tab-pane" id="more_details">
                        <h4>More Information</h4>
                        <div class="row">
                            <div class="col-md-4">
                                <ul class="list-group list-group-unbordered">

                                    <li class="list-group-item"><strong>Related Patient : </strong><a
                                                class="pull-right">
                                            {{$contact->user->firstname}}
                                            {{$contact->user->lastname}}
                                        </a></li>
                                    <li class="list-group-item"><strong>Relationship Type : </strong><a
                                                class="pull-right">{{ucwords($contact->relationship_type)}}</a></li>
                                    <li class="list-group-item"><strong>Created On : </strong><a
                                                class="pull-right">{{$contact->created_at->format('D jS, M, Y')}}</a>
                                    </li>

                                </ul>
                            </div>
                            <div class="col-md-8">
                                <div class="panel panel-success">
                                    <div class="panel-heading">
                                        More Information
                                    </div>
                                    <div class="panel-body">
                                        {!! $contact->more_info ?? 'No Information' !!}
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="tab-pane" id="med_history">

                        <div class="panel panel-success">
                            <div class="panel-heading">
                                Medical History
                            </div>
                            <div class="panel-body">
                                {!! $contact->medical_history ?? 'No Medical History' !!}
                            </div>
                        </div>
                    </div>

                    <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
            </div>
            <!-- /.nav-tabs-custom -->
        </div>
        <!-- /.col -->
    </div>
@endsection
