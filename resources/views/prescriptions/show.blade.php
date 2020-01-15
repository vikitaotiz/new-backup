@extends('adminlte::page')

@section('content')

    <div class="panel panel-success">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-4">
                    <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back
                    </button>
                </div>
                <div class="col-md-4 text-center">
                    <strong>Prescription Letter</strong>
                </div>
                <div class="col-md-4">
                    <a href="{{route('prescriptions.edit', $prescription->id)}}" class="pull-right btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a>
                </div>
            </div>
        </div>
    </div>

    <div class="panel panel-success">

        @include('inc.prescription.print_template_1.prescription')

        <div style="display: none">

            @include('inc.prescription.print_template_2.prescription')

        </div>

    </div>

    <div class="row" style="padding: 2%;">
        @if($drugs->count() > 0)
            <button onclick="printContent('invoice')" type="button" target="_blank" class="btn btn-info"><i
                        class="fa fa-print"></i> Print (Template 1)
            </button>

            <button onclick="printContent('invoice2')" type="button" target="_blank" class="btn btn-default"><i
                        class="fa fa-print"></i> Print (Template 2)
            </button>

            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#myModal"><i
                        class="fa fa-plus"></i>
                Add Prescription / Medication.
            </button>
        @endif

    </div>

    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add Prescription / Medication.</h4>
                </div>
                <div class="modal-body">

                    <form action="{{route('drugs.store')}}" method="post" style="padding: 1%;">

                        {{csrf_field()}}

                        <input type="hidden" name="prescription_id" value="{{ $prescription->id }}">
                        <div class="row">
                            <div class="col-md-6" id="medications">
                                <div class="form-group">
                                    <label>Rx Approved Name.
                                        <a href="#" data-toggle="modal" data-target="#addNewMedication">Create New</a>
                                    </label>
                                    <select name="medication_id" id="medication_id" class="form-control" required>
                                        @foreach ($medications as $medication)
                                            <option value="{{$medication->id}}">{{$medication->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Dose and Direction</label>
                                    <input type="text" name='dosage' class="form-control"
                                           placeholder="Enter dose and directions..." required>
                                </div>
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <label>Duration and Quantity</label>
                                <input type="text" name='duration_quantity' class="form-control"
                                       placeholder="Enter duration or quantity..." required>
                            </div>

                            <div class="col-md-6">
                                <label>Pharmacy (Dispensed by)</label>
                                <input type="text" name='dispensed_by' class="form-control"
                                       placeholder="Enter pharmacist name...">
                            </div>
                        </div>
                        <hr>

                        <div class="row">
                            <div class="col-md-6">
                                <button type="button" class="btn btn-default btn-block" data-dismiss="modal">Close
                                </button>
                            </div>
                            <div class="col-md-6">
                                <input type="submit" class="btn btn-sm btn-success btn-block"
                                       value="Submit Appointment">
                            </div>
                        </div>
                        <input name="user_id" type="hidden" value="{{$prescription->user_id}}">
                    </form>

                </div>
            </div>

        </div>
    </div>


    <div class="modal fade" id="addNewMedication" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add New Medication</h4>
                </div>
                <div class="modal-body">

                    {{-- <div class="form-group">
                            <label>Medication Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter Medication Name...">
                        </div>
                        <div class="form-group">
                            <label>Medication Description</label>
                            <textarea name="description" id="description" class="form-control" placeholder="Enter Medication Description...">
                            </textarea>
                        </div>


                       <div class="row" style="padding: 2%;">
                        <div class="col-md-6">
                            <button type="button" class="btn btn-default btn-block" data-dismiss="modal">Close</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-success btn-block" id="submitMedication" data-dismiss="modal">Submit Medication</button>
                        </div>
                    </div> --}}

                    <form action="{{route('prescription_medication.store')}}" method="post">

                        {{csrf_field()}}

                        <div class="form-group">
                            <label>Medication Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter Medication Name...">
                        </div>
                        <div class="form-group">
                            <label>Medication Description</label>
                            <textarea name="description" class="form-control"
                                      placeholder="Enter Medication Description..."></textarea>
                        </div>


                        <div class="row" style="padding: 2%;">
                            <div class="col-md-6">
                                <button type="button" class="btn btn-default btn-block" data-dismiss="modal">Close
                                </button>
                            </div>
                            <div class="col-md-6">
                                <input type="submit" class="btn btn-sm btn-success btn-block"
                                       value="Submit Appointment">
                            </div>
                        </div>
                    </form>

                </div>

            </div>

        </div>
    </div>
@endsection


