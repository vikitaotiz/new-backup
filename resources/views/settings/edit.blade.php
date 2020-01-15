@extends('adminlte::page')

@section('content')
   
 <div class="panel panel-success">
    <div class="panel-heading">
           <div class="row">
                 <div class="col-md-4">
                    <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                 </div>
                  <div class="col-md-4">
                     <strong>Edit Company Details</strong>
                  </div>
                  <div class="col-md-4">
               </div>
           </div>
    </div>

  <div class="panel-body">

      <form action="{{route('companydetails.update', $companydetail->id)}}" method="POST" enctype="multipart/form-data">
          {{csrf_field()}} {{method_field('PATCH')}}

          <div class="row">
            <div class="col-md-6">
              <label>Company Name</label>
              <input type="text" name="name" value="{{$companydetail->name}}" placeholder="Enter Company Name..." class="form-control">
            </div>
            <div class="col-md-6">
              <label>Address</label>
              <input type="text" name="address" value="{{$companydetail->address}}" placeholder="Enter Address..." class="form-control">
            </div>
          </div><hr>
          <div class="row">
            <div class="col-md-6">
              <label>Company Email</label>
              <input type="email" name="email" value="{{$companydetail->email}}" placeholder="Enter Company Email..." class="form-control">
            </div>
            <div class="col-md-6">
              <label>Phone</label>
              <input type="tel" name="phone" value="{{$companydetail->phone}}" placeholder="Enter Phone..." class="form-control">
            </div>
          </div><hr>

          <div class="row">
            <div class="col-md-6">
              <label>Company Logo</label>
              <input type="file" name="logo" class="form-control">
            </div>
            <div class="col-md-6">
              <label>Industry</label>
              <input type="text" name="industry" value="{{$companydetail->industry}}" placeholder="Enter Industry..." class="form-control">
            </div>
          </div><hr>

          <div class="row" style="padding-left:2%; padding-right:2%;">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control">
              <option value="1" {{old('status',$companydetail->status)=="1"? 'selected':''}}>Active</option>
              <option value="0" {{old('status',$companydetail->status)=="0"? 'selected':''}}>Inactive</option>
            </select>

          </div><hr>
          <div class="row" style="padding-left:2%; padding-right:2%;">
            <label>More Information</label>
            <textarea name="more_info" id="more_info" placeholder="Enter More Information...">
                {{$companydetail->more_info}}
            </textarea>
          </div><hr>
          <div class="row" style="padding-left:2%; padding-right:2%;">
              <input type="submit" class="btn btn-sm btn-primary btn-block" value="Update Company Details">
          </div>
        </form>


    </div>
</div>

    
@endsection
