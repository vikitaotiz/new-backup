@extends('adminlte::page')

@section('content')

 <div class="panel panel-success">
    <div class="panel-heading">
       <div class="row">
         <div class="col-md-4">
            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
         </div>
          <div class="col-md-4 text-center">
            <strong>Edit</strong>
          </div>
          <div class="col-md-4">
              <a href="{{ url('companydetails/'. $embedUrl->company_detail_id) }}" class="btn btn-info pull-right"><i class="fa fa-arrow-up"></i> Back to List</a>
          </div>
       </div>
    </div>
  <div class="panel-body">
      <form action="{{ route('embed_urls.update', $embedUrl->id) }}" method="post">
          @csrf
          @method('put')
          <div class="form-group row">
              <label for="doctor_id" class="col-sm-3 col-form-label">Select Doctor</label>
              <div class="col-sm-9">
                  <select name="doctor_id[]" id="doctor_id" class="form-control" multiple required>
                      <option value="all" @if($embedUrl->doctors->count() == 0) selected @endif>All</option>
                      @foreach($users as $user)
                          <option value="{{ $user->id }}" @if($embedUrl->doctors->contains('user_id', $user->id)) selected @endif>{{ $user->firstname }} {{ $user->lastname }}</option>
                      @endforeach
                  </select>
              </div>
          </div>
          <div class="form-group row">
              <label for="service_id" class="col-sm-3 col-form-label">Select Service</label>
              <div class="col-sm-9">
                  <select name="service_id[]" id="service_id" class="form-control" multiple required>
                      <option value="all" @if($embedUrl->services->count() == 0) selected @endif>All</option>
                      @foreach($embedUrl->services as $service)
                          <option value="{{ $service->service->id }}" selected>{{ $service->service->name }}</option>
                      @endforeach
                  </select>
              </div>
          </div>
          <div class="form-group row">
              <div class="col-sm-12">
                  <button type="submit" class="btn btn-primary pull-right">Update</button>
              </div>
          </div>
      </form>
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
