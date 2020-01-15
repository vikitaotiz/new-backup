@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                      <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>Edit Prescription</strong>
                        </div>
                        <div class="col-md-4">
                            {{-- <a href="{{route('prescriptions.create')}}" class="btn btn-sm btn-primary">Create New prescription</a> --}}
                        </div>
                    </div>
                </div>

                <div class="panel-body">
  
                        <form action="{{route('prescriptions.update', $prescription->id)}}" method="post" enctype="multipart/form-data">

                                {{csrf_field()}} {{method_field('PATCH')}}
                                
                                <div class="row">
                                  <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Assign Patient</label>
                                            <select name="user_id" id="user_id" class="form-control">
                                                @foreach ($users as $user)
                                                    <option value="{{$user->id}}"
                                                        @if($user->id == $prescription->user_id)
                                                           selected
                                                        @endif>
                                                            {{-- DOB : {{$user->date_of_birth->format('D jS, M, Y')}} -  --}}
                                                            {{$user->firstname}} {{$user->lastname}}
                                                            NHS : {{$user->nhs_number}}
                                                    </option>
                                                @endforeach
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
                                            <textarea name="drug_allergies" id="prescription1" class="form-control" placeholder="Enter Drug Allergies...">
                                                {{$prescription->drug_allergies}}
                                            </textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Comments</label>
                                            <textarea name="comments" id="prescription2" class="form-control" placeholder="Enter Comments...">
                                                {{$prescription->comments}}
                                            </textarea>
                                        </div>
                                    </div>
                                    
                                </div>

                                <div class="form-group">
                                    <input type="submit" value="Update Prescription" class="btn btn-sm btn-block btn-success">
                                </div>
                            </form>
                </div>
            </div>
    
@endsection
