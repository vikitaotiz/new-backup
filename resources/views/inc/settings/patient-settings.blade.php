<div class="row">
    <div class="col-md-12">
        <div class="box box-success box-solid">
          <div class="box-header with-border">
            <h3 class="box-title">Companies</h3>

            <div class="box-tools pull-right">
                @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
                <button type="button" class="btn btn-sm btn-default" data-toggle="modal" data-target="#modal-default">
                    Create Company
                  </button>
                @endif

              <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
              </button>
            </div>
            <!-- /.box-tools -->
          </div>
          <!-- /.box-header -->
          <div class="box-body" style="">

            @if($companydetails->count() > 0)
              <table class="table table-stripped">
                <thead>
                  <tr>
                    <th>Company Name</th>
                    <th>Company Phone</th>
                    <th>Company Address</th>
                    <th>Status</th>
                    <th>Created By</th>
                    <th>Created On</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($companydetails as $company)
                    <tr>
                      <td><a href="{{route('companydetails.show', $company->id)}}">{{$company->name}}</a></td>
                      <td>{{$company->phone}}</td>
                      <td>{{$company->address}}</td>
                      <td>{{$company->status ? 'Active' : 'Inactive'}}</td>
                      <td>{{App\User::find($company->user_id) ? App\User::find($company->user_id)->firstname : ''}}</td>
                      <td>{{$company->created_at->format('D jS, M, Y')}}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            @else
              <h4>No Companies Created...</h4>
            @endif
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
</div>


</div>
</div>

@if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
<div class="modal fade" id="modal-default">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">×</span></button>
<h4 class="modal-title">Company Details</h4>
</div>
<div class="modal-body">
<form action="{{route('companydetails.store')}}" method="POST" enctype="multipart/form-data">
{{csrf_field()}}

<div class="row">
  <div class="col-md-6">
    <label>Company Name</label>
    <input type="text" name="name" placeholder="Enter Company Name..." class="form-control">
  </div>
  <div class="col-md-6">
    <label>Address</label>
    <input type="text" name="address" placeholder="Enter Address..." class="form-control">
  </div>
</div><hr>
<div class="row">
  <div class="col-md-6">
    <label>Company Email</label>
    <input type="email" name="email" placeholder="Enter Company Email..." class="form-control">
  </div>
  <div class="col-md-6">
    <label>Phone</label>
    <input type="tel" name="phone" placeholder="Enter Phone..." class="form-control">
  </div>
</div><hr>

<div class="row">
  <div class="col-md-6">
    <label>Company Logo</label>
    <input type="file" name="logo" class="form-control">
  </div>
  <div class="col-md-6">
    <label>Industry</label>
    <input type="text" name="industry" placeholder="Enter Industry..." class="form-control">
  </div>
</div><hr>

<div class="row" style="padding-left:2%; padding-right:2%;">
  <label for="status">Status</label>
  <select name="status" id="status" class="form-control">
    <option value="1">Active</option>
    <option value="0">Inactive</option>
  </select>
</div><hr>

<div class="row" style="padding-left:2%; padding-right:2%;">
  <label>More Information</label>
  <textarea name="more_info" id="more_info" placeholder="Enter More Information..."></textarea>
</div><hr>
<div class="row">
  <div class="col-md-6">
      <button type="button" class="btn btn-default btn-block" data-dismiss="modal">Close</button>
  </div>
  <div class="col-md-6">
    <input type="submit" class="btn btn-sm btn-primary btn-block" value="Create Company">
  </div>
</div>
</form>

</div>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
@endif
