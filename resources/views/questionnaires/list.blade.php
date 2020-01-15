@extends('adminlte::page')

@section('title', 'Symptoms questionnaire')

@section('content')
    @if(Session::has('success'))
        <div class="alert alert-success alert-dismissible">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Success!</strong> {{ Session::get('success') }}
        </div>
    @endif

    <div class="panel panel-success">
        <div class="panel-heading">
             <div class="row">
                <div class="col-md-4">
                    <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                </div>
                <div class="col-md-4">
                    <strong>SymptomAid Beta</strong>
                </div>

            </div>
        </div>

        <div class="panel-body">
        @if(count($questionnaires) > 0)
            <table class="table table-bordered" id="users_table">
                <thead>
                    <tr>
                        <th>Symptom/Condition</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                        @foreach ($questionnaires as $questionnaire)
                        <tr>
                            <td>{{ $questionnaire->title }}</td>
                            <td>
                                <a href="{{route('questionnaires.single', $questionnaire->id)}}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> Show</a>
                            </td>
                         </tr>
                        @endforeach
                </tbody>
            </table>
        @else
            <h4>There are no Symptoms questionnaire</h4>
        @endif
        </div>
    </div>
    
@endsection

@section('js')
<script>
    $('#users_table').dataTable().fnDestroy();
    $('#users_table').DataTable({
        columns: [
            { "data": "Symptom/Condition"},
            { "data": "Actions", "orderable":false  }
        ],
        initComplete: function () {
            $('.dataTables_filter').css({'text-align': 'left' });
            $('.dataTables_filter label').css({'width': '100%', });
            $('.dataTables_filter input[type="search"]').css({ 'width': '90%' });
            $('.dataTables_filter input[type="search"]').attr("placeholder", "Search or select symptoms from list");
        }
    });
    
</script>
@endsection