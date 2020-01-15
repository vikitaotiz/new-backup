<table class="table table-bordered" id="referrals_table">
    <thead>
    <tr>
        <th>#ID</th>
        <th>Patient Name</th>
        <th>Patient Email</th>
        <th>Created By</th>
        <th>Drug Allergies</th>
        <th>Comments</th>
        <th>Signature</th>
        <th>Company Id</th>
        <th>User Id</th>
        <th>Created On</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($prescriptions as $prescription)
        @if(isset($prescription->user))
            @if($prescription->user != null)
                @if(isset($prescription->user->email))
                    <tr>
                        <td><a href="{{route('prescriptions.show', $prescription->id)}}">#{{$prescription->id}}</a></td>
                        <td>{{ $prescription->user->firstname . $prescription->user->lastname }}</td>
                        <td>{{ $prescription->user->email }}</td>
                        <td>
                            <?php $creator = App\User::find($prescription->creator_id); ?>
                            {{isset($creator) && $creator != null ? $creator->firstname : ''}}
                            {{isset($creator) && $creator != null ? $creator->lastname : ''}}
                        </td>
                        <td>{{$prescription->drug_allergies}}</td>
                        <td>{{$prescription->comments}}</td>
                        <td>{{$prescription->signature}}</td>
                        <td>{{$prescription->company_id}}</td>
                        <td>{{$prescription->user_id}}</td>
                        <td>{{isset($prescription->created_at) && $prescription->created_at != null ? $prescription->created_at->format('g:i A D jS, M, Y') : ''}}</td>
                    </tr>
                @endif
            @endif
        @endif
    @endforeach
    </tbody>
</table>
