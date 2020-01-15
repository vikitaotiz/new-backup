@section('css')

    <link href="{{ asset('css/Chart.min.css') }}" rel="stylesheet">

@stop

    <div class="col-lg-3 col-md-6 col-xs-12">
        <!-- small box -->
        <div class="small-box bg-green">
          <div class="inner">
            <h3>{{$patients}}</h3>

            <p>No. of Patients</p>
          </div>
          <div class="icon">
            <i class="ion ion-person-stalker"></i>
          </div>
          <a href="{{route('patients.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>

    @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 6)
      <div class="col-lg-3 col-md-6 col-xs-12">
        <!-- small box -->
        <div class="small-box bg-aqua">
          <div class="inner">
            <h3>{{$doctors}}</h3>

            <p>No. of Staff/Doctors</p>
          </div>
          <div class="icon">
            <i class="ion ion-person"></i>
          </div>
          <a href="{{route('users.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
      </div>
    @endif

    <!-- col -->
    <div class="col-lg-3 col-md-6 col-xs-12">
      <!-- small box -->
      <div class="small-box bg-yellow">
        <div class="inner">
          <h3>{{$appointmentsAll}}</h3>

          <p>No. of Appointments</p>
        </div>
        <div class="icon">
          <i class="ion ion-calendar"></i>
        </div>
        <a href="{{route('appointments.calendar')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- col -->
    <div class="col-lg-3 col-md-6 col-xs-12">
      <!-- small box -->
      <div class="small-box bg-red">
        <div class="inner">
          {{-- @if(!auth()->user()->role_id == 5) --}}
            <h3>{{$tasks}}</h3>
          {{-- @endif --}}
            {{-- <h3>{{auth()->user()->tasks->count()}}</h3> --}}

          <p>No. of Tasks</p>
        </div>
        <div class="icon">
          <i class="ion ion-clipboard"></i>
        </div>
        <a href="{{route('tasks.alltasks')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
      </div>
    </div>

    <!-- col -->
    <div class="col-lg-3 col-md-6 col-xs-12">
        <!-- small box -->
        <div class="small-box bg-blue-gradient">
            <div class="inner">
                <h3>{{$appointmentsAll}}</h3>

                <p>Bookings</p>
                <p>Since @if(App\Appointment::first()){{date('m/d/Y', strtotime(App\Appointment::first()->value('created_at')))}}@endif</p>
            </div>
            <div class="icon">
                <i class="ion ion-clipboard"></i>
            </div>
            <a href="{{route('appointments.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

    {{--@if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || (auth()->user()->role_id == 3 && auth()->user()->company))
    <!-- col -->
    <div class="col-lg-3 col-md-6 col-xs-12">
        <!-- small box -->
        <div class="small-box bg-olive">
            <div class="inner">
                <h3>350</h3>

                <p>Revenue in GBP</p>
                <p>Since 23/06/2019</p>
            </div>
            <div class="icon">
                <i class="ion ion-clipboard"></i>
            </div>
            <a href="javascript:void(0)" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>
    @endif--}}

    <!-- col -->
    <div class="col-lg-3 col-md-6 col-xs-12">
        <!-- small box -->
        <div class="small-box bg-gray">
            <div class="inner">
                <h3>{{$patients}}</h3>

                <p>Customers</p>
                <p>Since @if(App\User::first()){{date('m/d/Y', strtotime(App\User::first()->value('created_at')))}}@endif</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-stalker"></i>
            </div>
            <a href="{{route('patients.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

    <!-- col -->
    <div class="col-lg-3 col-md-6 col-xs-12">
        <!-- small box -->
        <div class="small-box bg-gray">
            <div class="inner">
                <h3>
                    @if ($patients)
                    {{round($appointmentsAll / $patients)}}
                    @else
                    0
                    @endif
                </h3>

                <p>Bookings per customer</p>
                <p>Since @if(App\User::first()){{date('m/d/Y', strtotime(App\User::first()->value('created_at')))}}@endif</p>
            </div>
            <div class="icon">
                <i class="ion ion-person-stalker"></i>
            </div>
            <a href="{{route('appointments.index')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
        </div>
    </div>

    @if(auth()->user()->role_id == 3 || auth()->user()->role_id == 4)
        <!-- col -->
        <div class="col-lg-3 col-md-6 col-xs-12" style="height: 179px">&nbsp;</div>
    @endif

    <!-- ./col -->
    <div class="col-lg-6 col-md-6 col-xs-12">
        <canvas id="myChart" width="400" height="400"></canvas>
    </div>

    <!-- ./col -->
    <div class="col-lg-6 col-md-6 col-xs-12">
        <canvas id="myChart2" width="400" height="400"></canvas>
    </div>

@section('js')

    <script src="{{ asset('js/Chart.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            var ctx = document.getElementById('myChart');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?php echo $appointmentsPastWeekLabel?>,
                    datasets: [{
                        label: 'Bookings for past 7 days',
                        data: <?php echo $appointmentsPastWeek?>,
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                stepSize: 1
                            }
                        }]
                    }
                }
            });

            var ctx2 = document.getElementById('myChart2');
            var myChart2 = new Chart(ctx2, {
                type: 'line',
                data: {
                    labels: <?php echo $appointmentsPostWeekLabel?>,
                    datasets: [{
                        label: 'Bookings for next 7 days',
                        data: <?php echo $appointmentsPostWeek?>,
                    }]
                },
                options: {
                    scales: {
                        yAxes: [{
                            ticks: {
                                stepSize: 1
                            }
                        }]
                    }
                }
            });
        });
    </script>

@stop
