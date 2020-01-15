<section class="invoice" id="invoice" style="padding: 2%;">
    <!-- title row -->
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">        

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
   </div>
    <!-- /.row -->
  </section>