<section class="invoice" id="invoice" style="padding: 2%;">
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
    </div><br><br>
   
   <div class="row" style="padding: 1%;">
        <strong>RE : Sicknote Letter</strong><hr>
   </div>

   <div class="row" style="padding: 1%;">
        <div class="panel panel-success">
          <div class="panel-heading">
            Content
          </div>
          <div class="panel-body">
              {!! $note->body !!}
          </div>
        </div>
   </div>
    <!-- /.row -->
  </section>