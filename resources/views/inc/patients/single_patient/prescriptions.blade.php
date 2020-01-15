
    <div class="row">
        <div class="col-md-6">
            <strong>Prescription Letters:</strong>
        </div>
        <div class="col-md-6">
            @if($patient->patient_note_text && $patient->patient_note_text != "")
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#patientNoteNew">Create Prescription Letter</button>
            @else
                <form action="{{route('patient_store_prescription.store')}}" method="post">
                    {{csrf_field()}}
                    <input type="hidden" name="user_id" value="{{$patient->id}}">
                    <input type="submit" value="Create Prescription Letter" class="btn btn-success pull-left">
                </form>
            @endif
        </div>
    </div>

    @if (count($patient->prescriptions) > 0)
        <table class="table table-bordered">
            <thead>
                <th>#ID</th>
                <th>Created By</th>
                <th>Created On</th>
                <th>Actions</th>
            </thead>
            <tbody>
                @foreach ($patient->prescriptions as $prescription)
                    <tr>
                        <td><a href="{{route('prescriptions.show', $prescription->id)}}">#{{$prescription->id}}</a></td>
                        <td>
                            {{App\User::find($prescription->creator_id)->firstname}}
                            {{App\User::find($prescription->creator_id)->lastname}}
                        </td>
                        <td>{{$prescription->created_at->format('g:i A D jS, M, Y')}}</td>
                        <td>
                            <form action="{{route('prescriptions.destroy', $prescription->id)}}" method="POST">
                                {{csrf_field()}}{{method_field('DELETE')}}
                                <a href="{{route('prescriptions.edit', $prescription->id)}}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a>
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        Patient has No Prescription Letters yet.
    @endif
