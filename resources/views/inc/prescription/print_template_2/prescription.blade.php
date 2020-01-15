<div id="invoice2">
<div class="panel-body" style="color: #004876;">

        <div class="row" style="padding:1%;">
            <table class="table">
                <tr>
                    <td>
                        <div class="col-md-4">
                            @if($prescription->user->doctor && count($prescription->user->doctor->companies))
                                @foreach($prescription->user->doctor->companies as $item)
                                <strong>{{$item->name}}</strong><br>
                                Address: {{$item->address}}<br>
                                Phone: {{$item->phone}}<br>
                                Email: {{$item->email}}
                                @endforeach
                            @else
                                <strong>{{$prescription->company->name}}</strong><br>
                                Address: {{$prescription->company->address}}<br>
                                Phone: {{$prescription->company->phone}}<br>
                                Email: {{$prescription->company->email}}
                            @endif
                        </div>
                    </td>
                    <td>
                        <div class="col-md-4">
                        </div></td>
                    <td>
                        <div class="col-md-4">
                            @if($prescription->user->doctor && count($prescription->user->doctor->companies))
                                @foreach($prescription->user->doctor->companies as $item)
                                    @if($item->logo)
                                        <img src="{{asset('/storage/'.$item->logo)}}" alt="Company Logo" width="100" height="60">
                                    @else
                                        <i class="fa fa-building"></i>
                                    @endif
                                @endforeach
                            @else
                                @if($prescription->company->logo)
                                    <img src="{{asset('/storage/'.$prescription->company->logo)}}" alt="Company Logo" width="100" height="60">
                                @else
                                    <i class="fa fa-building"></i>
                                @endif
                            @endif
                        </div>
                    </td>
                </tr>
            </table>

        </div><br>

        <div>
            <h3 class="text-center">Patient Details</h3>
            <table class="table">
                <tr>
                    <th>Patient Name : </th>
                    <td>{{$prescription->user->firstname}} {{$prescription->user->lastname}}</td>
                </tr>
                <tr>
                    <th>Address : </th>
                    <td>{{$prescription->user->address ?? 'N\A'}}</td>
                </tr>
                <tr>
                    <th>Date of birth : </th>
                    <td>{{$prescription->user->date_of_birth ?? 'N\A'}}</td>
                </tr>
                <tr>
                    <th>Date : </th>
                    <td>{{now()->format('D, jS, M, Y')}}</td>
                </tr>
                <tr>
                    <th>NHS Number : </th>
                    <td>{{$prescription->user->nhs_number ?? 'N\A'}}</td>
                </tr>
            </table>
        </div>

        <div>

        </div>


        <h3 class="text-center">Medication(s).</h3>

        @if($drugs->count() > 0)
            <table class="table" style="border-color:#E9F5E7; border-width: 10px">
                <thead>
                    <th>Rx (Approved Name)</th>
                    <th>Dose and Direction</th>
                    <th>Duration or Quantity</th>
                    @foreach($drugs as $drug)
                        @if($drug->dispensed_by)
                            <th>Pharmacy (Dispensed By)</th>
                        @endif
                    @endforeach
                </thead>
                <tbody>
                    @foreach($drugs as $drug)
                        <tr>
                            <td>{{$drug->medication->name}}</span></td>
                            <td>{{$drug->dosage}}</td>
                            <td>{{$drug->duration_quantity}}</td>
                            @if($drug->dispensed_by)
                                <td>{{$drug->dispensed_by}}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <table class="table" style="border-color:#E9F5E7; border-width: 10px">
                <tr>
                    <th>No Medication Added Yet. <a href="" data-toggle="modal" data-target="#myModal"> Click to add.</a></th>
                </tr>
            </table>
        @endif

        <div>
                <h3 class="text-center">Doctor Details</h3>

                <table class="table">
                    <tr>
                        <th>Dr Name : </th>
                        <td>
                            {{strtoupper($prescription->creator->firstname)}}
                            {{strtoupper($prescription->creator->lastname)}}
                        </td>
                    </tr>
                    <tr>
                        <th>Signature : </th>
                        <td>
                            @if($prescription->signature)
                                <img src="{{asset('/storage/'.$prescription->signature)}}" width="100" height="50" alt="Signature">
                            @else

                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>GMC : </th>
                        <td></td>
                    </tr>

                </table>
            </div>




    </div>
    <div class="panel-footer">
            <div style="padding: 1%;" class="text-center">
                    <strong>Name:</strong> {{$prescription->company->name}},
                    <strong>Address:</strong> {{$prescription->company->address}},
                    <strong>Phone:</strong> {{$prescription->company->phone}},
                    <strong>Email:</strong> {{$prescription->company->email}}.
            </div>
    </div>
</div>
