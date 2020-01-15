@extends('adminlte::page')

@section('title', 'SymptomAid')

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
                    <strong> SymptomAid Beta</strong>
                </div>
                <div class="col-md-4">
                    <a href="{{route('questionnaires.create')}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-user-plus"></i> Create  SymptomAid Beta</a>
                </div>
            </div>
        </div>

        <div class="panel-body">
        @if(count($questionnaires) > 0)
            <table class="table table-bordered" id="users_table">
                <thead>
                    <tr>
                        <th>Symptom/Condition </th>
                        {{--<th>Description</th>
                        <th>Image</th>--}}
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                        @foreach ($questionnaires as $questionnaire)
                        <tr>
                            <td>{{ $questionnaire->title }}</td>
                            {{--<td>{{ $questionnaire->description }}</td>
                            <td><img src="{{ asset('storage/'.$questionnaire->image) }}"></td>--}}
                            <td>
                                <div class="row">
                                    <div class="col-md-4">
                                        <a href="{{route('questionnaires.show', $questionnaire->id)}}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> Show</a>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="{{route('questionnaires.edit', $questionnaire->id)}}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a>
                                    </div>
                                    <div class="col-md-4">
                                        <form action="{{route('questionnaires.destroy', $questionnaire->id)}}" method="POST">
                                            {{csrf_field()}} {{method_field('DELETE')}}
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> Delete</button>
                                        </form>
                                    </div>
                                </div>
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
