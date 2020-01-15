<div class="row">
        <div class="col-md-6">
            <h4>Patient's Contacts</h4>
        </div>
        <div class="col-md-6">
            {{-- <a href="{{route('contacts.create')}}" class="btn btn-success btn-sm">Create Contact</a> --}}
            <a href="{{route('patientcontact.create', $patient->id)}}" class="btn btn-success btn-sm">Create Contact</a>
        </div>
    </div>
    
    @if (count($patient->contacts) > 0)
       <table class="table table-bordered" id="contacts_table">
            <thead>
                <th>Relative Name</th>
                <th>Relationship Type</th>
                <th>Created On</th>
            </thead>
            <tbody>
             @foreach ($patient->contacts as $contact)
                <tr>
                    <td>
                        <a data-toggle="collapse" href="#collapse{{$contact->id}}" aria-expanded="false" aria-controls="collapseExample">
                            {{$contact->relative_name}}</a>
                    
                            <div class="collapse" id="collapse{{$contact->id}}">
                                <div class="mt-5">
                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <strong>{{$contact->relative_name}}</strong>
                                                </div>
                                                <div class="col-md-6">
                                                    <a href="{{route('contacts.edit', $contact->id)}}">Click To Edit Contact Details</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <ul class="list-group list-group-unbordered">
                                                <li class="list-group-item"><strong>Date of Birth : </strong><a class="pull-right">{{$contact->date_of_birth->format('D, M, Y')}}</a></li>
                                                <li class="list-group-item"><strong>NHS Number : </strong><a class="pull-right">{{$contact->nhs_number ?? 'N/A'}}</a></li>
                                                <li class="list-group-item"><strong>Phone : </strong><a class="pull-right">{{$contact->phone}}</a></li>
                                                <li class="list-group-item"><strong>Email : </strong><a class="pull-right">{{$contact->email}}</a></li>
                                                <li class="list-group-item"><strong>Relationship Type : </strong><a class="pull-right">{{ucwords($contact->relationship_type)}}</a></li>
                                                <li class="list-group-item"><strong>Medical History : </strong><a class="pull-right">{!! $contact->medical_history ?? 'No Medical History' !!}</a></li>
                                                <li class="list-group-item"><strong>More Information : </strong><a class="pull-right">{!! $contact->more_info ?? 'No Information' !!}</a></li>
                                            </ul>
                                        </div>
                                        <div class="panel-footer">
                                             {{$contact->created_at->format('D jS, M, Y')}}
                                        </div>
                                    </div>
                                </div>
                              </div> 
                    </td>
                                        
                    <td>{{$contact->relationship_type}}</td>
                    <td>{{$contact->created_at->diffForHumans()}}</td>
                </tr>
              @endforeach
              </tbody>
           </table>
          @else
              Patient has No contacts or related patients yet.
          @endif