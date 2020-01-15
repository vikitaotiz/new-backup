@extends('adminlte::page')

@section('content')

        <div class="panel panel-success">
            <div class="panel-heading">
                    <div class="row">
                    <div class="col-md-4">
                        <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i>  Back</button>
                    </div>
                    <div class="col-md-4">
                        <strong>Patient Name :
                            {{$consultation->user->firstname}}
                            {{$consultation->user->lastname}}
                        </strong>
                    </div>
                    <div class="col-md-4">
                        <a href="{{route('initialnotes.edit', $consultation->id)}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-pencil"></i>  Edit consultation</a>
                    </div>
                </div>
            </div>
        </div>
        @include('inc.tabMenu', ['tabMenuPosition'=>5, 'patient_id'=>$consultation->user_id])
        <section class="invoice" id="invoice">
                <!-- title row -->
                <div class="row">
                  <div class="col-xs-12">
                    <h2 class="page-header">
                        @if($consultation->user->doctor && count($consultation->user->doctor->companies))
                            @foreach($consultation->user->doctor->companies as $item)
                                @if($item->logo)
                                    <img src="{{asset('/storage/'.$item->logo)}}" alt="Company Logo" width="100" height="60">
                                @else
                                    <i class="fa fa-building"></i>
                                @endif
                                    {{$item->name}}.
                            @endforeach
                        @else
                            @if($consultation->company->logo)
                                <img src="{{asset('/storage/'.$consultation->company->logo)}}" alt="Company Logo" width="100" height="60">
                            @else
                                <i class="fa fa-building"></i>
                            @endif
                                {{$consultation->company->name}}.
                        @endif
                      <small class="pull-right">Date: {{date('Y, M, jS, D')}}</small>
                    </h2>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- info row -->
                <div class="row invoice-info">
                  <div class="col-sm-4 invoice-col">
                    From
                    <address>
                        @if($consultation->user->doctor && count($consultation->user->doctor->companies))
                            @foreach($consultation->user->doctor->companies as $item)
                                <strong>{{$item->name}}</strong><br>
                                Address: {{$item->address}}<br>
                                Phone: {{$item->phone}}<br>
                                Email: {{$item->email}}
                            @endforeach
                        @else
                            <strong>{{$consultation->company->name}}</strong><br>
                            Address: {{$consultation->company->address}}<br>
                            Phone: {{$consultation->company->phone}}<br>
                            Email: {{$consultation->company->email}}
                        @endif
                    </address>
                  </div>
                  <div class="col-sm-4 invoice-col">
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4 invoice-col">
                    To
                    <address>
                      <strong>
                        {{$consultation->user->firstname}}
                        {{$consultation->user->lastname}}
                      </strong><br>
                      Address: {{$consultation->user->address}}<br>
                      Phone: {{$consultation->user->phone}}<br>
                      Email: {{$consultation->user->email}}
                    </address>
                  </div>

                  <!-- /.col -->
                </div><br>
                <strong>RE : Initial Treatment Note</strong>
                <hr>

               <div class="row" style="margin: 2%;">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    Presenting Complain
                                </div>
                                <div class="panel-body">
                                    {!! $consultation->complain ?? '' !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    History of Presenting Complaint
                                </div>
                                <div class="panel-body">
                                    {!! $consultation->history_presenting_complaint ?? '' !!}
                                </div>
                            </div>
                        </div>
                    </div><hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    Past Medical History
                                </div>
                                <div class="panel-body">
                                    {!! $consultation->past_medical_history ?? '' !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    Family History
                                </div>
                                <div class="panel-body">
                                    {!! $consultation->family_history ?? '' !!}
                                </div>
                            </div>
                        </div>
                    </div><hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    Social History
                                </div>
                                <div class="panel-body">
                                    {!! $consultation->social_history ?? '' !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    Drug History
                                </div>
                                <div class="panel-body">
                                    {!! $consultation->drug_history ?? '' !!}
                                </div>
                            </div>
                        </div>
                    </div><hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    Drug Allergies
                                </div>
                                <div class="panel-body">
                                    {!! $consultation->drug_allergies ?? '' !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    Examination
                                </div>
                                <div class="panel-body">
                                    {!! $consultation->examination ?? '' !!}
                                </div>
                            </div>
                        </div>
                    </div><hr>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    Diagnosis
                                </div>
                                <div class="panel-body">
                                    {!! $consultation->diagnosis ?? '' !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    Treatment
                                </div>
                                <div class="panel-body">
                                    {!! $consultation->treatment ?? '' !!}
                                </div>
                            </div>
                        </div>
                    </div><hr>
                <p><strong>Created On : </strong>{{$consultation->created_at->format('g:i A D jS, M, Y')}}.
                   <strong>Created By : </strong>{{App\User::findOrfail($consultation->creator_id)->firstname ?? ''}}</p>
               </div>

            </section>
                <!-- /.row -->
            <div class="row no-print" style="padding: 2%;">
                <div class="col-xs-12">
                    <button onclick="printContent('invoice')" type="button" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Print</a></button>
                    <button type="button" class="btn btn-success pull-right"><i class="fa fa-envelope"></i>
                        Mail Letter
                    </button>

                </div>
            </div>



@endsection
