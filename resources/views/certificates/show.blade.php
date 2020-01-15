@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                     <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i>  Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>{{$certificate->name}}</strong>
                        </div>
                        <div class="col-md-4">
                            <a href="{{route('certificates.edit', $certificate->id)}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-pencil"></i>  Edit certificate</a>
                        </div>
                    </div>
                </div>
         </div>

         <section class="invoice" id="invoice" style="padding: 2%;">
                <!-- title row -->
                <div class="row">
                  <div class="col-xs-12">
                    <h2 class="page-header">        
                      @if($certificate->company->logo)
                        <img src="{{asset('/storage/'.$certificate->company->logo)}}" alt="Company Logo" width="50" height="30">
                      @else
                        <i class="fa fa-building"></i>
                      @endif 
                       {{$certificate->company->name}}.

                      <small class="pull-right">Date: {{date('d/m/Y')}}</small>
                    </h2>
                  </div>
                  <!-- /.col -->
                </div>
                <!-- info row -->
                <div class="row invoice-info">
                  <div class="col-sm-4 invoice-col">
                    From
                    <address>
                      <strong>{{$certificate->company->name}}</strong><br>
                        Address: {{$certificate->company->address}}<br>
                        Phone: {{$certificate->company->phone}}<br>
                        Email: {{$certificate->company->email}}
                    </address>
                  </div>
                  <div class="col-sm-4 invoice-col">
                  </div>
                  <!-- /.col -->
                  <div class="col-sm-4 invoice-col">
                    To
                    <address>
                      <strong>
                        {{$certificate->user->firstname}}
                        {{$certificate->user->lastname}}
                      </strong><br>
                      Address: {{$certificate->user->address}}<br>
                      Phone: {{$certificate->user->phone}}<br>
                      Email: {{$certificate->user->email}}
                    </address>
                  </div>
                 
                  <!-- /.col -->
                </div><br><br>
               
               <div class="row" style="padding: 1%;">
                    <strong>RE : Certificate Letter.</strong><hr>
               </div>

               <div class="row" style="padding: 1%;">
                  <div class="panel panel-success">
                      <div class="panel-heading">
                        Content
                      </div>
                      <div class="panel-body">
                          {!! $certificate->body !!}
                      </div>
                    </div>
               </div><hr>
                <!-- /.row -->
              </section>

              <div class="row no-print" style="padding: 2%;">
                  <div class="col-xs-12">
                      <button onclick="printContent('invoice')" type="button" target="_blank" class="btn btn-info"><i class="fa fa-print"></i> Print</button>
                      <button type="button" class="btn btn-success pull-right"><i class="fa fa-envelope"></i>
                          Mail Letter
                      </button>
                      
                  </div>
              </div>
    
@endsection
