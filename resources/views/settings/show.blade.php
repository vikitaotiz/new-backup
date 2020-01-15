@extends('adminlte::page')

@section('content')

 <div class="panel panel-success">
    <div class="panel-heading">
           <div class="row">
                 <div class="col-md-2">
                    <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                 </div>
                  <div class="col-md-4">
                    <strong>{{$company->name}}</strong>
                  </div>
                  <div class="col-md-6">
                      <div class="row">
                          <div class="col-md-6">
                            <a href="{{route('companydetails.edit', $company->id)}}" class="btn btn-sm btn-success"><i class="fa fa-pencil"></i> Edit Company</a>
                          </div>
                          <div class="col-md-6">
                              @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
                              <form action="{{route('companydetails.destroy', $company->id)}}" method="post">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure want to delete the whole company?')"><i class="fa fa-trash"></i> Delete Company</button>
                              </form>
                              @endif
                          </div>
                      </div>
               </div>
           </div>
    </div>

  <div class="panel-body">
    <div class="row" style="padding: 1%;">
        <div class="col-md-6">
            <img src="{{asset('/storage/'.$company->logo) ?? 'No Logo'}}" alt="Company Logo" width="400" height="400">
        </div>
        <div class="col-md-6">
            <ul class="list-group list-group-unbordered panel panel-success" style="padding: 1%;">
                  <div class="panel-heading">
                        <h4 class="text-center">Company Details</h4>
                </div>
                <li class="list-group-item"><strong>Phone : </strong><a class="pull-right">{{$company->phone}}</a></li>
                <li class="list-group-item"><strong>Address : </strong><a class="pull-right">{{$company->address}}</a></li>
                <li class="list-group-item"><strong>Email : </strong><a class="pull-right">{{$company->email}}</a></li>
                <li class="list-group-item"><strong>Created On : </strong><a class="pull-right">{{$company->created_at->format('D jS, M, Y')}}</a></li>
                <li class="list-group-item"><strong>Status : </strong><a class="pull-right">{{$company->status ? 'Active' : 'Inactive'}}</a></li>
                <li class="list-group-item"><strong>Created By : </strong><a class="pull-right">
                    {{App\User::findOrfail($company->user_id)->firstname}}</a></li>
                <li class="list-group-item"><strong>UUID : </strong><a class="pull-right">{{$company->uuid}}</a></li>
                <li class="list-group-item"><strong>Embed url : </strong><a class="pull-right" href="{{ route('appointment.book').'?clinic='.$company->uuid}}" target="_blank">{{ route('appointment.book').'?clinic='.$company->uuid}}</a></li>
             </ul>
        </div>
    </div><hr>
    <div style="padding: 1%;">
        <div class="row panel panel-success" style="padding: .5%;">
            <div class="panel-heading">
                More Information
            </div>
            {!! $company->more_info !!}
        </div>
    </div>

      <div class="panel panel-info">
          <div class="panel-heading">
              <div class="row">
                  <div class="col-md-4">
                      <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                  </div>
                  <div class="col-md-4 text-center">
                      <strong>Embed URLs</strong>
                  </div>
                  <div class="col-md-4 pull-right">
                      <button class="pull-right btn btn-success" data-toggle="modal" data-target="#customUrlGeneratorModal"><i class="fa fa-plus-circle"></i> Add</button>
                  </div>
              </div>
          </div>
          <div class="panel-body">
              @if(count($urls) > 0)
                  <table class="table table-bordered">
                      <thead>
                      <tr>
                          <th>Doctors</th>
                          <th>Services</th>
                          <th>Embed URL</th>
                          <th>Action</th>
                      </tr>
                      </thead>
                      <tbody>

                      @foreach ($urls as $url)
                          <tr>
                              <td>
                                  @if(count($url->doctors))
                                      @foreach($url->doctors as $doctor)
                                          @if($loop->last)
                                              {{ $doctor->user->firstname }} {{ $doctor->user->lastname }}
                                          @else
                                              {{ $doctor->user->firstname }} {{ $doctor->user->lastname }},
                                          @endif
                                      @endforeach
                                  @else
                                      All
                                  @endif
                              </td>
                              <td>
                                  @if(count($url->services))
                                      @foreach($url->services as $service)
                                          @if($loop->last)
                                              {{ $service->service->name }}
                                          @else
                                              {{ $service->service->name }},
                                          @endif
                                      @endforeach
                                  @else
                                      All
                                  @endif
                              </td>
                              <td><a href="{{ url('/book-appointment?id='.$url->id) }}" target="_blank">{{ url('/book-appointment?id='.$url->id) }}</a></td>
                              <td>
                                  <form action="{{route('embed_urls.destroy', $url->id)}}" method="POST">
                                      {{csrf_field()}}{{method_field('DELETE')}}
                                      <a href="{{route('embed_urls.edit', $url->id)}}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a>
                                      <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> Delete</button>
                                  </form>
                              </td>
                          </tr>
                      @endforeach

                      </tbody>
                  </table>
              @else
                  <h4>There are no embed url</h4>
              @endif
          </div>
      </div>
  </div>

     <!-- Modal -->
     <div id="customUrlGeneratorModal" class="modal fade" role="dialog">
         <div class="modal-dialog">

             <!-- Modal content-->
             <div class="modal-content">
                 <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal">&times;</button>
                     <h4 class="modal-title">Generate custom Embed url</h4>
                 </div>
                 <div class="modal-body">
                     <form action="{{ route('embed_urls.store') }}" method="post">
                         @csrf
                         <input type="hidden" name="company_detail_id" value="{{ $company->id }}">
                         <input type="hidden" name="uuid" value="{{ $company->uuid }}">
                         <div class="form-group row">
                             <label for="doctor_id" class="col-sm-3 col-form-label">Select Doctor</label>
                             <div class="col-sm-9">
                                 <select name="doctor_id[]" id="doctor_id" class="form-control" multiple required>
                                     <option value="all" selected>All</option>
                                     @foreach($users as $user)
                                     <option value="{{ $user->id }}">{{ $user->firstname }} {{ $user->lastname }}</option>
                                     @endforeach
                                 </select>
                             </div>
                         </div>
                         <div class="form-group row">
                             <label for="service_id" class="col-sm-3 col-form-label">Select Service</label>
                             <div class="col-sm-9">
                                 <select name="service_id[]" id="service_id" class="form-control" multiple required>
                                     <option value="all" selected>All</option>
                                 </select>
                             </div>
                         </div>
                         <div class="form-group row">
                             <div class="col-sm-12">
                                 <button type="submit" class="btn btn-primary pull-right">Generate</button>
                             </div>
                         </div>
                     </form>
                 </div>
             </div>

         </div>
     </div>

</div>

@endsection

@section('js')
    <script>
        // Get services by doctor
        $('#doctor_id').on('select2:select', function (e) {
            var data = e.params.data;
            var $select = $('#doctor_id');

            // If selected all remove rest options else remove all
            if (data.id == 'all') {
                $select.val(null).trigger('change');
                $select.val(data.id).trigger('change');
            } else {
                var idToRemove = 'all';

                var values = $select.val();
                if (values) {
                    var i = values.indexOf(idToRemove);
                    if (i >= 0) {
                        values.splice(i, 1);
                        $select.val(values).change();
                    }
                }

                // Make ajax call to get doctor by service
                $.ajax({
                    type: 'get',
                    url: window.location.origin + '/service-by-doctor/' + data.id,
                    success: function (response) {
                        if (jQuery.isEmptyObject(response)) {
                            alert('No service found for this doctor!');
                        } else {
                            for (var key in response) {
                                $('#service_id').append('<option value="' + response[key].id + '">' + response[key].name + '</option>');
                            }
                        }
                    }
                });
            }
        });

        $('#service_id').on('select2:select', function (e) {
            var data = e.params.data;
            var $select = $('#service_id');

            // If selected all remove rest options else remove all
            if (data.id == 'all') {
                $select.val(null).trigger('change');
                $select.val(data.id).trigger('change');
            } else {
                var idToRemove = 'all';

                var values = $select.val();
                if (values) {
                    var i = values.indexOf(idToRemove);
                    if (i >= 0) {
                        values.splice(i, 1);
                        $select.val(values).change();
                    }
                }
            }
        });
    </script>
@stop
