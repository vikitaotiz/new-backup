<table class="table table-bordered" id="invoices_table">
    <thead>
    <tr>
        <th>Patient Name</th>
        <th>Patient Email</th>
        <th>Invoice No.</th>
        <th>Product / Service</th>
        <th>Product / Service Id</th>
        <th>Due date</th>
        <th>Doctor Assigned</th>
        <th>Insurance name</th>
        <th>Description</th>
        <th>Code serial</th>
        <th>Quantity</th>
        <th>Charge Id</th>
        <th>Tax Id</th>
        <th>Currency Id</th>
        <th>Company Id</th>
        <th>Doctor Id</th>
        <th>User Id</th>
        <th>Created On</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($invoices as $invoice)
        @if(isset($invoice->user))
            @if($invoice->user != null)
                @if(isset($invoice->user->email))
                    <?php $product_service = App\Service::find($invoice->product_service); ?>
                    <?php $doctor = App\User::find($invoice->product_service); ?>
                    <tr>
                        <td>{{ $invoice->user->firstname . $invoice->user->lastname }}</td>
                        <td>{{ $invoice->user->email }}</td>
                        <td><a href="{{route('invoices.show', $invoice->id)}}">#00HN - {{$invoice->id}}</a></td>
                        <td>{{isset($product_service) && $product_service != null ? $product_service->name : ''}}</td>
                        <td>{{$invoice->product_service}}</td>
                        <td>{{$invoice->due_date}}</td>
                        <td>{{isset($doctor) && $doctor != null ? $doctor->firstname : ''}}</td>
                        <td>{{$invoice->insurance_name}}</td>
                        <td>{{$invoice->description}}</td>
                        <td>{{$invoice->code_serial}}</td>
                        <td>{{$invoice->quantity}}</td>
                        <td>{{$invoice->charge_id}}</td>
                        <td>{{$invoice->tax_id}}</td>
                        <td>{{$invoice->currency_id}}</td>
                        <td>{{$invoice->company_id}}</td>
                        <td>{{$invoice->doctor_id}}</td>
                        <td>{{$invoice->user_id}}</td>
                        <td>{{isset($invoice->created_at) && $invoice->created_at != null ? $invoice->created_at->diffForHumans() : ''}}</td>
                    </tr>
                @endif
            @endif
        @endif
    @endforeach
    </tbody>
</table>
