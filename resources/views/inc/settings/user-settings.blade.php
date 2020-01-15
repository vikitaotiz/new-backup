<div class="row">
    @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2 || auth()->user()->role_id == 6)
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-tasks"></i></span>

                <div class="info-box-content">
            <span class="info-box-text">
              <a href="{{route('tasks.alltasks')}}">Tasks</a>
             </span>
                    <span class="info-box-number">
                  {{$tasks}}
            </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-user"></i></span>

                <div class="info-box-content">
          <span class="info-box-text">
            <a href="{{route('patients.index')}}">Patients</a>
          </span>
                    <span class="info-box-number">
                {{$patients}}
          </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>

        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-user"></i></span>

                <div class="info-box-content">
          <span class="info-box-text">
            <a href="{{route('users.index')}}">Staff/Doctor</a>
          </span>
                    <span class="info-box-number">
                {{$doctors}}
          </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        @if(auth()->user()->role_id == 1 || auth()->user()->role_id == 2)
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-key"></i></span>

                    <div class="info-box-content">
          <span class="info-box-text">
            <a href="{{route('roles.index')}}">Roles</a>
          </span>
                        <span class="info-box-number">
                {{App\Role::all()->count()}}
          </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
        @endif

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-dollar"></i></span>

                <div class="info-box-content">
          <span class="info-box-text">
                <a href="{{route('taxes.index')}}">Taxes</a>
          </span>
                    <span class="info-box-number">
                {{$taxes}}
          </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        {{-- </div><br>

        <div class="row"> --}}
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-credit-card-alt"></i></span>

                <div class="info-box-content">
              <span class="info-box-text">
                 <a href="{{route('charges.index')}}">Charges</a>
              </span>
                    <span class="info-box-number">
                  {{$charges}}
              </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-money"></i></span>

                <div class="info-box-content">
              <span class="info-box-text">
                <a href="{{route('currencies.index')}}">Currencies</a>
               </span>
                    <span class="info-box-number">
                    {{$currencies}}
              </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-bars"></i></span>

                <div class="info-box-content">
                <span class="info-box-text">
                  <a href="{{route('services.index')}}">Services</a>
                 </span>
                    <span class="info-box-number">
                      {{$services}}
                </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-credit-card"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">
                    <a href="{{route('paymentmethods.index')}}">Payment Methods</a>
                   </span>
                    <span class="info-box-number">
                        {{$paymentmethods}}
                  </span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-medkit"></i></span>

                <div class="info-box-content">
              <span class="info-box-text">
                 <a href="{{route('medications.index')}}">Medications</a>
              </span>
                    <span class="info-box-number">
                  {{$medications}}
              </span>
                </div>
                <!-- /.info-box-content -->
            </div>

            <!-- /.info-box -->
        </div>


        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-trash"></i></span>

                <div class="info-box-content">
              <span class="info-box-text">
                 <a href="{{route('users.trashed')}}">Trashed Users</a>
              </span>
                    <span class="info-box-number">
                  {{ $trashed }}
              </span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-file-excel-o"></i></span>

                <div class="info-box-content">
                  <span class="info-box-text">
                     <a href="{{route('excel.export')}}">Export patients</a>
                  </span>
                    <span class="info-box-number">

                  </span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-file-excel-o"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">
                         <a href="{{route('excel.create')}}">Import patients</a>
                      </span>
                    <span class="info-box-number">

                      </span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-sticky-note"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">
                         <a href="{{route('templates.index')}}">Treatment note templates</a>
                      </span>
                    <span class="info-box-number">

                      </span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-fw fa-question"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">
                         <a href="{{route('questionnaires.index')}}">Symptoms questionnaire</a>
                      </span>
                    <span class="info-box-number">

                      </span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-bar-chart-o"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">
                         <a href="{{route('bodycharts.index')}}">Body charts</a>
                      </span>
                    <span class="info-box-number">

                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>

        <div class="col-md-3 col-sm-6 col-xs-12">
            <div class="info-box">
                <span class="info-box-icon bg-green"><i class="fa fa-briefcase"></i></span>

                <div class="info-box-content">
                    <span class="info-box-text">
                         <a href="{{route('jobs.index')}}">SMS SCHEDULER</a>
                      </span>
                    <span class="info-box-number">

                    </span>
                </div>
                <!-- /.info-box-content -->
            </div>
        </div>

        @if(auth()->user()->role_id == 6)
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-green"><i class="fa fa-cc-stripe" aria-hidden="true"></i></span>

                    <div class="info-box-content">
                    <span class="info-box-text">
                        @if(auth()->user()->stripe_user_id)
                            <a href="{{ route('stripe.disconnect') }}"><span class="text-danger">Disconnect from Stripe</span></a>
                        @else
                            <a target="_blank" href="https://connect.stripe.com/oauth/authorize?response_type=code&client_id={{ env('STRIPE_CLIENT_ID') }}&scope=read_write">Connect with Stripe</a>
                        @endif
                      </span>
                        <span class="info-box-number">
                        @if(auth()->user()->stripe_user_id)
                                Stripe Connected
                                <p><strong></strong> </p>
                            @else
                                Stripe Not Connected
                            @endif
                    </span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
            </div>
        @endif
@endif
</div>
<br>
