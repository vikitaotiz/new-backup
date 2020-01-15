<div class="panel-body" style="color: #004876;" id="invoice">
                    
        <div class="row" style="padding:1%;">
            <div class="col-md-4">
            </div>
            <div class="col-md-4">
            </div>
            <div class="col-md-4">
                <strong>
                      @if($prescription->company->logo)
                        <img src="{{asset('/storage/'.$prescription->company->logo)}}" alt="Company Logo" width="50" height="30">
                      @else
                        <i class="fa fa-building"></i>
                      @endif <br>
                       {{$prescription->company->name}}.
                    {{$prescription->company->name}}</strong><br>
                Address: {{$prescription->company->address}}<br>
                Phone: {{$prescription->company->phone}}<br>
                Email: {{$prescription->company->email}}
            </div>
        </div>

       

        <table class="table">
            <tr>
                    <td>
                        <div style="border: 3px solid #E9F5E7; padding: 1%;">
                            <h4 class="text-center"><b>DRUG ALLERGIES</b></h4><hr>
                            {!! $prescription->drug_allergies !!}
                        </div>
                    </td>
                    <td>
                            <div style="border: 7px solid #E9F5E7; padding: 1%;">
                                    <table class="table">
                                        <tr>
                                            <td>
                                                    <b>Title</b><br>
                                                    {{ucwords($prescription->user->title)}} <br> <br>
                                                    <b>Surname</b><br>
                                                    {{$prescription->user->firstname}} <br> <br>
                                                    <b>Address</b><br>
                                                    {{$prescription->user->address}} <br> <br>
                                                    <b>Consultant/Doctor</b><br>
                                                    {{$prescription->creator->firstname}} 
                                                    {{$prescription->creator->lastname}}
                                                    <br>
                                            </td>
                                            <td>
                                                <b>Forenames</b><br>
                                                {{$prescription->user->lastname}} <br> <br>
                                                <b>Date of Birth</b><br>
                                                {{$prescription->user->date_of_birth}} <br><br>
                                                <b>NHS / Clinic Number</b><br>
                                                {{$prescription->user->nhs_number}} <br>
                                            </td>
                                        </tr>
                                    </table>

                                </div>
                    </td>
            </tr>
        </table>

        @if($drugs->count() > 0)
            <table class="table table-bordered" style="border-color:#E9F5E7; border-width: 10px">
                <thead>
                    <th>Rx (Approved Name)</th>
                    <th>Dose and Direction</th>
                    <th>Duration or Quantity</th>
                    <th>Pharmacy (Dispensed By)</th>
                </thead>
                <tbody>
                    @foreach($drugs as $drug)
                        <tr>
                            <td><a href="{{route('medications.edit', $drug->id)}}">{{$drug->medication}}</a></span></td>
                            <td>{{$drug->dosage}}</td>
                            <td>{{$drug->duration_quantity}}</td>
                            <td>{{$drug->dispensed_by}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <table class="table table-bordered" style="border-color:#E9F5E7; border-width: 10px">
                <tr>
                    <th>No Medication Added Yet.
                </tr>
            </table>
        @endif

        <table class="table table-bordered" style="border-color:#E9F5E7; border-width: 10px">
            <thead>
                <th>Dr's Signature</th>
                <th>Dr's Name in Block Capitals</th>
                <th>Date</th>
            </thead>
            <tbody>
                <td>
                   
                    @if($prescription->signature)
                       <img src="{{asset('/storage/'.$prescription->signature)}}" width="100" height="50">
                    @else
                        
                    @endif
                    
                </td>
                <td>
                    {{strtoupper($prescription->creator->firstname)}}
                    {{strtoupper($prescription->creator->lastname)}}
                </td>
                <td>{{$prescription->created_at->format('D d, M, Y')}}</td>
                <tr>
                    <td colspan="3">
                        <h4><b>COMMENTS</b></h4><hr>
                        {!! $prescription->comments !!}
                    </td>
                </tr>
            </tbody>
        </table>
        <div style="padding: 1%;">

        <div class="row" style="background: #E9F5E7;">
            <h4 class="text-center">
                <b>PLEASE TAKE THIS PRESCRIPTION TO PHARMACY TO BE DISPENSED</b>
            </h4>
        </div>
        </div>
       
</div>