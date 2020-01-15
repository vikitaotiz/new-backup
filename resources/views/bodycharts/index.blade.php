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
                    <strong>Bodycharts</strong>
                </div>
                <div class="col-md-4">
                    <a href="{{route('bodycharts.create')}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-user-plus"></i> Create body chart</a>
                </div>
            </div>
        </div>

        <div class="panel-body">
        @if(count($bodycharts) > 0)
            <table class="table table-bordered" id="bodycharts_table">
                <thead>
                    <tr>
                        <th>S/L</th>
                        <th>Body chart</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @php($i=1)
                        @foreach ($bodycharts as $bodychart)
                        <tr>
                            <td>{{ $i++ }}</td>
                            <td class="text-center"><img src="{{ asset('storage/'.$bodychart->link) }}" style="width:200px;"></td>
                            <td>
                                <form action="{{route('bodycharts.destroy', $bodychart->id)}}" method="POST">
                                    {{csrf_field()}} {{method_field('DELETE')}}
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> Delete</button>
                                </form>
                            </td>
                         </tr>
                        @endforeach
                </tbody>
            </table>
        @else
            <h4>There are no body chart</h4>
        @endif
        </div>
    </div>
@endsection

@section('js')
<script>
    $('#bodycharts_table').dataTable().fnDestroy();
    $('#bodycharts_table').DataTable({
        columns: [
            { "data": "S/L", "orderable":false},
            { "data": "Body chart", "orderable":false},
            { "data": "Actions", "orderable":false}
        ],
        "searching": false
    });
</script>
@endsection
