@if (count($patient->prescriptions) > 0)
    <table class="table table-bordered" id="referrals_table">
        <thead>
        <tr>
        <th>#ID</th>
        <th>Created By</th>
        <th>Created On</th>
        </tr>
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
            </tr>
        @endforeach
        </tbody>
    </table>
@else
    Patient has No Prescription Letters yet.
@endif
