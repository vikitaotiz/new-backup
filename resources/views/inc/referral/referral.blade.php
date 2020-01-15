<section class="invoice" id="invoice">
        <!-- title row -->
        <div class="row">
          <div class="col-xs-12">
            <h2 class="page-header">        
               {{$referral->company->name}}.
               
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
                <strong>{{$referral->company->name}}</strong><br>
                Address: {{$referral->company->address}}<br>
                Phone: {{$referral->company->phone}}<br>
                Email: {{$referral->company->email}}
            </address>
          </div>
          <div class="col-sm-4 invoice-col">
          </div>
          <!-- /.col -->
          <div class="col-sm-4 invoice-col">
       
          </div>
         
          <!-- /.col -->
        </div><br><br>
       
       <div class="row" style="margin-left: 2%;">
            <strong>RE : Refferal Letter.</strong><hr>
       </div>

       <div class="row" style="margin-left: 2%;">
        <div class="panel panel-success">
          <div class="panel-heading">
            Content
          </div>
          <div class="panel-body">
              {!! $referral->body !!}
          </div>
        </div>
       </div>
        <!-- /.row -->
      </section>