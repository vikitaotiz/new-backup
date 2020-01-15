@extends('adminlte::page')

@section('title', 'Treatment note templates')

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
                    <strong>Treatment note templates</strong>
                </div>
                <div class="col-md-4">
                    <a href="{{route('templates.create')}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-user-plus"></i> Create New Template</a>
                </div>
            </div>
        </div>

        <div class="panel-body">
        @if(count($templates) > 0)
            <table class="table table-bordered" id="users_table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th># of Sections</th>
                        <th># of Questions</th>
                        <th>Include when printing</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                        @foreach ($templates as $template)
                        <tr>
                            <td>{{ $template->title }}</td>
                            <td>{{ $template->sections->count() }}</td>
                            <td>{{ $template->questions->count() }}</td>
                            <td>
                                @if($template->is_show_patients_address)
                                    Address,
                                @endif
                                @if($template->is_show_patients_dob)
                                    Date of birth,
                                @endif
                                @if($template->is_show_patients_nhs_number)
                                    Medicare,
                                @endif
                                @if($template->is_show_patients_referral_source)
                                    Occupation,
                                @endif
                                @if($template->is_show_patients_occupation)
                                        Reference #
                                @endif
                            </td>
                            <td>
                                <div class="row">
                                    <div class="col-md-4">
                                        <a href="{{route('templates.edit', $template->id)}}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a>
                                    </div>
                                    <div class="col-md-8">
                                        <form action="{{route('templates.destroy', $template->id)}}" method="POST">
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
            <h4>There are no templates</h4>
        @endif
        </div>
    </div>

@endsection
