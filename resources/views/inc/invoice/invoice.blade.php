<section class="invoice" id="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-xs-12">
        <h2 class="page-header">
           {{$invoice->company->name}}.
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
          <strong>{{$invoice->company->name}}</strong><br>
          Address: {{$invoice->company->address}}<br>
          Phone: {{$invoice->company->phone}}<br>
          Email: {{$invoice->company->email}}
        </address>
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        
      </div>
      <!-- /.col -->
      <div class="col-sm-4 invoice-col">
        <b>Invoice </b>#00HN - {{$invoice->id}}<br>
        <br>
        <b>Payment Due:</b>
        @if($invoice->due_date)
          {{$invoice->due_date->format('D jS, M,Y')}}
        @else 
          <p>Not Set</p>
        @endif
        <br>

      </div>
      <!-- /.col -->
    </div><br><br>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
      <div class="col-xs-12 table-responsive">
        <table class="table table-bordered" style="table-layout: fixed; width: 100%;">
          <thead class="bg-success">
          <tr>
            <th>Product / Service</th>
            <th style="width: 25%;">Description</th>
            <th>Code / Serial</th>
            <th>Quantity</th>
            <th>Amount Per Item ({{App\Currency::find($invoice->currency_id)->symbol}})</th>
            <th>Total ({{App\Currency::find($invoice->currency_id)->symbol}})</th>
          </tr>
          </thead>
          <tbody>
            <tr>
              <td>{{App\Service::findOrfail($invoice->product_service)->name}}</td>

              <td>{!! $invoice->description !!}</td>
              <td>{{$invoice->code_serial}}</td>
              <td>{{$invoice->quantity}}</td>
              <td>
                  {{App\Charge::find($invoice->charge_id)->amount}} 
              </td>
              <td>
                  {{($invoice->quantity) * (App\Charge::find($invoice->charge_id)->amount)}}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- /.col -->
    </div><br><br>
    <!-- /.row -->

    <div class="row">
      <!-- accepted payments column -->
      <div class="col-xs-6">
        
      </div>
      <!-- /.col -->
      <div class="col-xs-6">
        <p class="lead">Amount Due</p>

        <div class="table-responsive">
          <table class="table">
            <tbody>
            <tr>
              <th>Subtotal ({{App\Currency::find($invoice->currency_id)->symbol}})</th>
              <td>
                  {{($invoice->quantity) * (App\Charge::find($invoice->charge_id)->amount)}}
              </td>
            </tr>
            <tr>
              <th>Tax </th>
              <td>
                @if($invoice->tax_id)
                  {{App\Tax::find($invoice->tax_id)->name}} {{App\Tax::find($invoice->tax_id)->rate}}%</td>
                @else
                    No Tax
                @endif
            </tr>
            <tr>
              <th>Total ({{App\Currency::find($invoice->currency_id)->symbol}})</th>
              <td>

                  @if($invoice->tax_id)
                    {{
                      (((App\Tax::find($invoice->tax_id)->rate)/100) *
                      (($invoice->quantity) * 
                      (App\Charge::find($invoice->charge_id)->amount))) +
                      (($invoice->quantity) * (App\Charge::find($invoice->charge_id)->amount))
                    }}
                  @else
                    {{
                      (($invoice->quantity) * 
                      (App\Charge::find($invoice->charge_id)->amount))
                     }}
                  @endif

                  
    
              </td>
            </tr>
          </tbody></table>
        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>