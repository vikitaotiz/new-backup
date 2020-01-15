@extends('adminlte::page')

@section('content')
   
    <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>All contacts</strong>
                        </div>
                        <div class="col-md-4">
                            <a href="{{route('contacts.create')}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-user-plus"></i> Create New contact</a>
                        </div>
                    </div>
                </div>

                <div class="panel-body">

                        @if(count($contacts) > 0)
                        <table class="table table-bordered" id="contacts_table">
                            <thead>
                                <tr>
                                    <th>NHS Number</th>
                                    <th>Name</th>
                                    <th>Created On</th>
                                    {{-- <th>Created By</th> --}}
                                    <th>Related Patient</th>
                                    <th>Relationship Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                    @foreach ($contacts as $contact)
                                    <tr>
                                        <td>{{$contact->nhs_number ?? 'N/A'}}</td>
                                        <td>{{$contact->relative_name}}</td>
                                        <td>{{$contact->created_at->format('D jS, M, Y')}}</td>
                                        {{-- <td>{{$contact->user->name ?? 'N/A'}}</td> --}}
                                        <td>
                                            {{$contact->user->firstname}}
                                            {{$contact->user->lastname}}
                                        </td>
                                        <td>{{ucwords($contact->relationship_type)}}</td>
                                        <td>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <a href="{{route('contacts.show', $contact->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> View</a>
                                                </div>
                                                <div class="col-md-8">
                                                    <form action="{{route('contacts.destroy', $contact->id)}}" method="POST">
                                                        {{csrf_field()}}{{method_field('DELETE')}}
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
                        <h4>There are no contacts</h4>
                    @endif

                </div>
            </div>
    
@endsection
