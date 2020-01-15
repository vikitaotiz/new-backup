<div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">Create Patient</h4>
      </div>
      <div class="modal-body">
          <form action="{{ url('/patient_ajax') }}" method="post">
        {{csrf_field()}}

        <div class="row">

                <div class="col-md-4">
                    <div class="form-group">
                          <label>First Name *</label>
                          <input type="text" name="firstname" id="firstname" value="{{old('firstname')}}" class="form-control" placeholder="Enter First Name...">
                    </div>
                </div>
                <div class="col-md-4">
                     <div class="form-group">
                          <label>Last Name *</label>
                          <input type="text" name="lastname" id="lastname" value="{{old('lastname')}}" class="form-control" placeholder="Enter Last Name...">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Gender *</label>
                        <select name="gender" id="gender" class="form-control">
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>
                </div>
            </div><hr>

            <div class="row">


                <div class="col-md-4">
                    <div class="form-group">
                        <label>Date of Birth *</label>
                        <input type="text" class="form-control date_of_birth" id="date_of_birth" value="{{old('date_of_birth')}}" name="date_of_birth" placeholder="Select Date of Birth">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Phone *</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{old('phone')}}" placeholder="Phone Number...">
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>Email *</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{old('email')}}" placeholder="Enter Email">
                    </div>
                </div>


            </div><hr>

            <div class="row">
                    <div class="col-md-12">
                        <label>Password *</label>
                        <div class="form-group has-feedback {{ $errors->has('password') ? 'has-error' : '' }}">
                            <input type="password" name="password" id="password" class="form-control"
                                   placeholder="{{ trans('adminlte::adminlte.password') }}">
                            <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div>

                </div><hr>
                <input type="hidden" name="role_id" value="5">
        <div class="row" style="padding: 2%;">
            <div class="col-md-6">
                <button type="button" class="btn btn-default btn-block" data-dismiss="modal">Close</button>
            </div>
            <div class="col-md-6">
                <button type="submit" class="btn btn-sm btn-success btn-block">Submit Appointment</button>
            </div>
        </div>
          </form>
      </div>

    </div>
    <!-- /.modal-content -->
  </div>
