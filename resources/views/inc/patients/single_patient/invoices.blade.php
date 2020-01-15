<div class="row">
        <div class="col-md-6">
            <h4>Patient's Invoices</h4>
        </div>
        <div class="col-md-6">
            {{-- <a href="{{route('invoices.create')}}" class="btn btn-success btn-sm">Create Invoice</a> --}}
            <a href="{{route('patientinvoice.create', $patient->id)}}" class="btn btn-success btn-sm">Create Invoice</a>
        </div>
    </div>

    @if (count($patient->invoices) > 0)
        <table class="table table-bordered" id="invoices_table">
            <thead>
            <tr>
                <th>Invoice No.</th>
                <th>Product / Service</th>
                <th>Due date</th>
                <th>Doctor Assigned</th>
                <th>Created On</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($patient->invoices as $invoice)
                    <tr>
                        <td>
                           <a data-toggle="collapse" href="#collapseInvoice{{$invoice->id}}">#00HN - {{$invoice->id}}</a>
                           <div class="collapse" id="collapseInvoice{{$invoice->id}}">
                                <div class="mt-3">
                                     <div class="panel panel-success">
                                          <div class="panel-heading">
                                              <div class="row">
                                                  <div class="col-md-4">
                                                     <strong>#00HN - {{$invoice->id}}</strong>
                                                   </div>
                                                    <div class="col-md-4">
                                                        <a href="{{route('invoices.show', $invoice->id)}}">Click To View More</a>
                                                    </div>
                                                        <div class="col-md-4">
                                                            <a href="{{route('invoices.edit', $invoice->id)}}">Click To Edit Invoice Details</a>
                                                        </div>
                                                    </div>
                                            </div>

                                            <div class="panel-body">
                                               
                                                    <div class="row">
                                                            <div class="col-xs-12 table-responsive">
                                                              <table class="table" style="table-layout: fixed; width: 100%;">
                                                                <thead class="bg-success">
                                                                <tr>
                                                                  <th>Product / Service</th>
                                                                  <th style="width: 25%;">Description</th>
                                                                  {{-- <th>Code / Serial</th> --}}
                                                                  <th>Quantity</th>
                                                                  <th>Amount Per Item ({{$invoice->currency ? $invoice->currency->symbol : '$'}})</th>
                                                                  <th>Total ({{$invoice->currency ? $invoice->currency->symbol : '$'}})</th>
                                                                </tr>
                                                                </thead>
                                                                <tbody>
                                                                  <tr>
                                                                    <td>{{($invoice->service ? $invoice->service->name : 'no service')}}</td>
                                                  
                                                                    <td>{!! $invoice->description !!}</td>
                                                                    {{-- <td>{{$invoice->code_serial}}</td> --}}
                                                                    <td>{{$invoice->quantity}}</td>
                                                                    <td>
                                                                        {{($invoice->charge ? $invoice->charge->amount : 0)}} 
                                                                    </td>
                                                                    <td>
                                                                        
                                                                        {{($invoice->quantity) * ($invoice->charge ? $invoice->charge->amount : 0)}}
                                                                    </td>
                                                                  </tr>
                                                                </tbody>
                                                              </table>
                                                            </div>
                                                            <!-- /.col -->
                                                          </div><br><br>
                                                          <!-- /.row -->
                                                    

                                            </div>

                                            <div class="panel-footer">
                                                {{$invoice->created_at->diffForHumans()}}  
                                            </div>
                                </div>
                              </div>
                        </td>
                        <td>{{($invoice->service ? $invoice->service->name : 'no service')}}</td>
                        <td>{{$invoice->due_date ?? 'Not Set'}}</td>
                        <td>{{($invoice->creator ? $invoice->creator->firstname .' '. $invoice->creator->lastname : '')}}</td>
                        <td>{{$invoice->created_at->diffForHumans()}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        Patient has No tasks yet.
    @endif
