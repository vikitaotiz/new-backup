<section class="invoice" id="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">   
              
               {{$note->company->name}}.
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
                        {!! $note->complain ?? '' !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        History of Presenting Complaint
                    </div>
                    <div class="panel-body">
                        {!! $note->history_presenting_complaint ?? '' !!}
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
                        {!! $note->past_medical_history ?? '' !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        Family History
                    </div>
                    <div class="panel-body">
                        {!! $note->family_history ?? '' !!}
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
                        {!! $note->social_history ?? '' !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        Drug History
                    </div>
                    <div class="panel-body">
                        {!! $note->drug_history ?? '' !!}
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
                        {!! $note->drug_allergies ?? '' !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        Examination
                    </div>
                    <div class="panel-body">
                        {!! $note->examination ?? '' !!}
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
                        {!! $note->diagnosis ?? '' !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="panel panel-success">
                    <div class="panel-heading">
                        Treatment
                    </div>
                    <div class="panel-body">
                        {!! $note->treatment ?? '' !!}
                    </div>
                </div>
            </div>
        </div><hr>
    
   </div>

</section>