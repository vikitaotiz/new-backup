<!-- ./col -->
<div class="col-lg-6 col-md-6 col-xs-12">
  <!-- small box -->
  <div class="small-box bg-yellow">
    <div class="inner">
      <h3>{{auth()->user()->appointments->count()}}</h3>

      <p>No. of Appointments</p>
    </div>
    <div class="icon">
      <i class="ion ion-calendar"></i>
    </div>
    <a href="{{route('appointments.calendar')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
  </div>
</div>
<!-- ./col -->
<div class="col-lg-6 col-md-6 col-xs-12">
  <!-- small box -->
  <div class="small-box bg-red">
    <div class="inner">
      <h3>My Profile</h3>

      <p>Appointments, Contacts....</p>
    </div>
    <div class="icon">
      <i class="ion ion-clipboard"></i>
    </div>
    <a href="{{route('users.my_profile')}}" class="small-box-footer">Profile info <i class="fa fa-arrow-circle-right"></i></a>
  </div>
</div>