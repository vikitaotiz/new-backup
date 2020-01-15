<section class="invoice">
        <!-- title row -->
        <div class="row">
          <div class="col-xs-12">
            <h2 class="page-header">        

                   {{$note->company->name}}.
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
                <strong>{{$note->company->name}}</strong><br>
                Address: {{$note->company->address}}<br>
                Phone: {{$note->company->phone}}<br>
                Email: {{$note->company->email}}
            </address>
          </div>
          <div class="col-sm-4 invoice-col">
          </div>
          <!-- /.col -->
          <div class="col-sm-4 invoice-col">
            
          </div>
         
          <!-- /.col -->
        </div><br><hr>
        <strong>RE : Follow Up note Note.</strong>
       <div class="row" style="margin: 2%;" id="invoice">
        
        <div class="col-md-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    Patient Progress
                </div>
                <div class="panel-body">
                    {!! $note->patient_progress !!}
                </div>
            </div>
            <div class="panel panel-success">
                <div class="panel-heading">
                    Patient Assessment
                </div>
                <div class="panel-body">
                    {!! $note->assessment !!}
                </div>
            </div>
            <div class="panel panel-success">
                <div class="panel-heading">
                    Plan
                </div>
                <div class="panel-body">
                    {!! $note->plan !!}
                </div>
            </div>
        </div>
     
       
       </div><hr>
      </section>