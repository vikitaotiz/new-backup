
<table class="table table-bordered" id="documents_table">
    <thead>
    <tr>
        <th>Patient Name</th>
        <th>Patient Email</th>
        <th>File</th>
        <th>File Name</th>
        <th>User Id</th>
        <th>Created By</th>
        <th>Created On</th>
    </tr>
    </thead>
    <tbody>
        @foreach ($files as $file)
            @if(isset($file->user))
                @if($file->user != null)
                    @if(isset($file->user->email))
                    <tr>
                        <td>{{ $file->user->firstname . $file->user->lastname }}</td>
                        <td>{{ $file->user->email }}</td>
                        <td>
                            <a href="{{route('files.show',$file->id)}}">
                                {{$file->name}}
                            </a>
                        </td>
                        <td>
                            <a href="../storage/{{$file->filename}}" target="_blank" class="pull-right">
                                <i class="fa fa-external-link">{{$file->filename}}</i>
                            </a>
                        </td>
                        <td>{{$file->user_id}}</td>
                        <td>{{$file->user->firstname}}</td>
                        <td>{{isset($file->created_at) && $file->created_at != null ? $file->created_at->format('g:i A D jS, M, Y') : ''}}</td>
                    </tr>
                    @endif
                @endif
            @endif
        @endforeach
    </tbody>
</table>
