<div class="panel panel-success">
    <div class="panel-heading">
           More Information
    </div>
    <div class="panel-body">
            {!! $user->more_info ?? 'No Information' !!}
    </div>
    </div>

    @if ($user->role_id != 6)
      @if ($user->availability == 1)
          <div class="panel panel-success">
      @else
          <div class="panel panel-danger">
      @endif
          <div class="panel-heading">
              <div class="row">
                  <div class="col-md-8">
                          Availability as of the current date :
                          @if ($user->availability == 1)
                              <span style="text-decoration: underline">Available</span>
                          @else
                              <span style="text-decoration: underline">Not Available</span>
                          @endif
                  </div>
                  <div class="col-md-4">
                      <a href="#" data-toggle="modal" data-target="#changeAvailabilityStatus">Change Availability Status</a>
                  </div>
              </div>

          </div>
        </div>
    @endif
</div>
