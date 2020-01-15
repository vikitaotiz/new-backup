@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                      <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                        <strong>Create Prescription for {{$user->firstname}} {{$user->lastname}}</strong>
                        </div>
                        <div class="col-md-4">
                            {{-- <a href="{{route('prescriptions.create')}}" class="btn btn-sm btn-primary">Create New prescription</a> --}}
                        </div>
                    </div>
                </div>

                <div class="panel-body">
  
                        <form action="{{route('patient_store_prescription.store')}}" method="post" enctype="multipart/form-data">

                                {{csrf_field()}}

                                <input type="hidden" name="user_id" value="{{$user->id}}">
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Assign Patient</label>
                                            <select name="user_id" id="patients_list" class="form-control" disabled>
                                                    <option value="{{$user->id}}">
                                                            {{$user->firstname}} {{$user->lastname}} - 
                                                            NHS : {{$user->nhs_number}} - 
                                                            {{-- DOB : {{$user->date_of_birth->format('D jS, M, Y')}} --}}
                                                    </option>
                                            </select>
                                        </div>
                                    </div>
                                     <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Signature</label>
                                            <input type="file" name="signature" class="form-control">
                                        </div>
                                    </div>
                                   
                                </div>

                                <div class="row">
                
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Drug Allergies</label>
                                            <textarea name="drug_allergies" class="form-control" id="prescription1" placeholder="Enter drug allergies...">
                                                {{old('drug_allergies')}}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Comments</label>
                                            <textarea name="comments" class="form-control" id="prescription2" placeholder="Enter Comments...">
                                                {{old('comments')}}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input type="submit" value="Submit Prescription" class="btn btn-sm btn-block btn-success">
                                </div>
                            </form>
                </div>
            </div>
    
@endsection
