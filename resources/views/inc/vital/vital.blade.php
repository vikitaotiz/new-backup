<section class="invoice" id="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">        

              {{$vital->company->name}}.
           
          <small class="pull-right">Date: {{date('D, M, jS, Y')}}</small>
        </h2>
      </div>
      <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        From
        <address>
            <strong>{{$vital->company->name}}</strong><br>
            Address: {{$vital->company->address}}<br>
            Phone: {{$vital->company->phone}}<br>
            Email: {{$vital->company->email}}
        </address>
      </div>
      <div class="col-sm-4 invoice-col">
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        
      </div>
     
      <!-- /.col -->
    </div><br><hr>

   <div class="row" style="margin: 2%;">
      <table class="table table-bordered">
          <tr>
            <th>Temperature:</th>
            <td>{{$vital->temperature}}</td>
          </tr>

          <tr>
            <th>Pulse Rate:</th>
            <td>{{$vital->pulse_rate}}</td>
          </tr>
          
          <tr>
            <th>Systolic BP:</th>
            <td>{{$vital->systolic_bp}}</td>
          </tr>

          <tr>
            <th>Diastolic BP:</th>
            <td>{{$vital->diastolic_bp}}</td>
          </tr>

          <tr>
            <th>Respiratory Rate:</th>
            <td>{{$vital->respiratory_rate}}</td>
          </tr>

          <tr>
            <th>Oxygen Saturation:</th>
            <td>{{$vital->oxygen_saturation}}</td>
          </tr>

          <tr>
            <th>O2 Administered:</th>
            <td>{{$vital->o2_administered}}</td>
          </tr>

          <tr>
            <th>Pain:</th>
            <td>{{$vital->pain}}</td>
          </tr>

          <tr>
            <th>Head Circumference:</th>
            <td>{{$vital->head_circumference}}</td>
          </tr>

          <tr>
            <th>Height:</th>
            <td>{{$vital->height}}</td>
          </tr>

          <tr>
            <th>Weight:</th>
            <td>{{$vital->weight}}</td>
          </tr>

      </table>
   </div><hr>

  </section>