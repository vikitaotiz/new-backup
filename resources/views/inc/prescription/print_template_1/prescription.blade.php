<div class="panel-body" style="color: #004876;" id="invoice">
    @include('inc.tabMenu', ['tabMenuPosition'=>6, 'patient_id'=>$prescription->user_id])
    <div class="row" style="padding:1%;">
        <div class="col-md-4">
        </div>
        <div class="col-md-4">
        </div>
        <div class="col-md-4">
            @if($prescription->user->doctor && count($prescription->user->doctor->companies))
                @foreach($prescription->user->doctor->companies as $item)
                    <strong>
                    @if($item->logo)
                        <img src="{{asset('/storage/'.$item->logo)}}" alt="Company Logo" width="100" height="60">
                    @else
                        <i class="fa fa-building"></i>
                    @endif <br>
                        {{$item->name}}
                    </strong><br>
                    Address: {{$item->address}}<br>
                    Phone: {{$item->phone}}<br>
                    Email: {{$item->email}}
                @endforeach
            @else
                <strong>
                @if($prescription->company->logo)
                    <img src="{{asset('/storage/'.$prescription->company->logo)}}" alt="Company Logo" width="100" height="60">
                @else
                    <i class="fa fa-building"></i>
                @endif <br>
                    {{$prescription->company->name}}
                </strong><br>
                    Address: {{$prescription->company->address}}<br>
                    Phone: {{$prescription->company->phone}}<br>
                    Email: {{$prescription->company->email}}
            @endif

        </div>
    </div>

    <table class="table">
        <tr>
            <td style="width:50%">
                <div style="border: 3px solid #E9F5E7; padding: 1%;">
                    <h4 class="text-center"><b>DRUG ALLERGIES</b></h4>
                    <hr>
                    <i class="fas fa-pencil-alt pull-right no-print" id="editAllergy"
                       onclick="editAllergyFunction(this)"></i>
                    <p style="padding:5px">
                        <i class="fas fa-times pull-right no-print" id="cancelAllergy" onclick="cancelAllergy()"
                           style="display:none"></i>
                        <span id="alergyText">{!! $prescription->drug_allergies !!}</span>
                    </p>
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
        <table class="table" style="border-color:#E9F5E7; border-width: 10px">
            <thead>
            <th>Rx (Approved Name)</th>
            <th>Dose and Direction</th>
            <th>Duration or Quantity</th>
            <th>Pharmacy (Dispensed By)</th>
            </thead>
            <tbody>
            @foreach($drugs as $drug)
                <tr>
                    <td>{{$drug->medication->name}}</td>
                    <td>{{$drug->dosage}}</td>
                    <td>{{$drug->duration_quantity}}</td>
                    <td>{{$drug->dispensed_by}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <table class="table" style="border-color:#E9F5E7; border-width: 10px">
            <tr>
                <th>No Medication Added Yet. <a href="" data-toggle="modal" data-target="#myModal"> Click to add.</a>
                </th>
            </tr>
        </table>
    @endif

    <table class="table" style="border-color:#E9F5E7; border-width: 10px">
        <thead>
        <th>Dr's Signature</th>
        <th>Dr's Name in Block Capitals</th>
        <th>Date</th>
        </thead>
        <tbody>
        <td>

            <i class="fas fa-pencil-alt pull-right no-print" id="editSigniture" onclick="editSignitureFunction()"></i>
            <p style="padding:5px; display:none" id="signatureSpan">
                <i class="fas fa-times pull-right no-print" id="cancelSigniture" onclick="cancelSignature()"></i>
                <input class="no-print" type="file" id="signature" onblur="signatureChange(this)"
                       onchange="signatureChange(this)"/>
            </p>
            <span id="signatureImage">
                @if($prescription->signature)
                <img src="{{url('/uploads/'.$prescription->signature)}}" width="100" height="50">
                @else
                <div style="border:1px solid blue; height: 50px;width: 90%"></div>
                @endif
            </span>
        </td>
        <td>
            {{strtoupper($prescription->creator->firstname)}}
            {{strtoupper($prescription->creator->lastname)}}
        </td>
        <td>{{$prescription->created_at->format('D d, M, Y')}}</td>
        <tr>
            <td colspan="3">
                <h4><b>COMMENTS</b></h4>
                <hr>
                <i class="fas fa-pencil-alt pull-right no-print" id="editComments"
                   onclick="editCommentsFunction(this)"></i>
                <p style="padding:5px">
                    <i class="fas fa-times pull-right no-print" id="cancelComments" onclick="cancelComments()"
                       style="display:none"></i>
                    <span id="commentsText">{!! $prescription->comments !!}</span>
                </p>
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

<script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script>
    function editAllergyFunction() {
        var alergyText = $('#alergyText').text();
        $('#alergyText').html(`<textarea onblur="dragAllergyChange(this)" name="drug_allergies" class="form-control no-print" style="margin:auto;resize:none;" placeholder="Enter drug allergies..." onchange="dragAllergyChange(this)">` + alergyText + `</textarea>`);
        $('#editAllergy').hide();
        $('#cancelAllergy').show();
        $('textarea[name="drug_allergies"]').focus();
    }

    function cancelAllergy() {
        $('#alergyText').text($('textarea[name="comments"]').val());
        $('#cancelAllergy').hide();
        $('#editAllergy').show();
    }

    function dragAllergyChange(th) {
        if ($(th).val() != '') {
            upload('all', $(th).val());
            $('#alergyText').text($(th).val());
            $('#editAllergy').show();
            $('#cancelAllergy').hide();
        }
    }

    function editCommentsFunction() {
        var commentsText = $('#commentsText').text();
        $('#commentsText').html(`<textarea onblur="commentsChange(this)" name="comments" class="form-control no-print" style="margin:auto;resize:none;" placeholder="Enter drug allergies..." onchange="commentsChange(this)">` + commentsText + `</textarea>`);
        $('#editComments').hide();
        $('#cancelComments').show();
        $('textarea[name="comments"]').focus();
    }

    function cancelComments() {
        $('#commentsText').text($('textarea[name="comments"]').val());
        $('#cancelComments').hide();
        $('#editComments').show();
    }

    function commentsChange(th) {
        if ($(th).val() != '') {
            upload('com', $(th).val());
            $('#commentsText').text($(th).val());
            $('#editComments').show();
            $('#cancelComments').hide();
        }
    }

    function editSignitureFunction() {
        $('#signatureSpan').show();
        $('#editSigniture').hide();
        $('#signature').focus();
    }

    function cancelSignature() {
        $('#signatureSpan').hide();
        $('#editSigniture').show();
    }


    function signatureChange(th) {
        if ($(th).val() != '') {
            readURL(th);
            upload('sig', th);
            $('#signatureSpan').hide();
            $('#editSigniture').show();
        }
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#signatureImage').html(`<img src="`+e.target.result+`" width="100" height="50">`);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function upload(type, val) {
        var form_data = new FormData();
        if (type == 'sig') {
            form_data.append('file', val.files[0]);
        }
        if (type == 'all') {
            form_data.append('allergy', val);
        }
        if (type == 'com') {
            form_data.append('commentdata', val);
        }

        form_data.append('_token', '{{csrf_token()}}');
        form_data.append('id', '{{$prescription->id}}');

        $('#loading').css('display', 'block');

        $.ajax({
            url: "{{ route('prescriptionsEdit') }}",
            data: form_data,
            type: 'POST',
            contentType: false,
            processData: false,
            success: function (data) {
            },
            error: function (xhr, status, error) {
                alert(xhr.responseText);
                $('#preview_image').attr('src', '{{asset('images/noimage.jpg')}}');
            }
        });
    }
</script>
