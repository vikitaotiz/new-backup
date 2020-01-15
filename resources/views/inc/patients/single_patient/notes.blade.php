<h4 class="text-center">Patient's Notes</h4><hr>
<!-- Custom Tabs -->
<div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li @if(!Session::has('newTemplate')) class="active" @endif><a href="#initialnotee" data-toggle="tab">Initial Consultations</a></li>
      <li><a href="#followupnote" data-toggle="tab">Follow Up Consultations</a></li>
      <li><a href="#vital" data-toggle="tab">Vitals</a></li>
      <li @if(Session::has('newTemplate')) class="active" @endif><a href="#treatment-note-1" data-toggle="tab">Template</a></li>
    </ul>
    <div class="tab-content">

      <div  @if(!Session::has('newTemplate')) class="active tab-pane" @else class="tab-pane" @endif id="initialnotee">
            <div class="row">
                    <div class="col-md-6">
                        <strong>Initial Consultation Notes:</strong>
                    </div>
                    <div class="col-md-6">
                        <a href="{{route('patientinitialnote.create', $patient->id)}}" class="btn btn-success btn-sm">Create Initial Consultation Note</a>
                    </div>
            </div>
            <hr>

        @if (count($patient->initialconsultations) > 0)
              <table class="table table-bordered" id="initialnotes_table">
                  <thead>
                      <th>#ID</th>
                      <th>Presenting Complaint</th>
                      <th>Created On</th>
                  </thead>
                  <tbody>
                      @foreach ($patient->initialconsultations as $note)
                          <tr>
                              <td>{{$note->id}}</td>
                              <td><a href="{{route('initialnotes.show', $note->id)}}">{!! $note->complain !!}</a></td>
                              <td>{{$note->created_at->diffForHumans()}}</td>
                          </tr>
                      @endforeach
                  </tbody>
              </table>
          @else
              Patient has No Initial Consultation Notes yet.
          @endif

      </div>
      <!-- /.tab-pane -->
      <div class="tab-pane" id="followupnote">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Follow Up Consultation Notes:</strong>
                    </div>
                    <div class="col-md-6">
                        <a href="{{route('patientfollowupnote.create', $patient->id)}}" class="btn btn-success btn-sm">Create Follow Up Consultation Note</a>
                    </div>
                </div>
                <hr>

              @if (count($patient->followupconsultations) > 0)
              <table class="table table-bordered" id="followupnotes_table">
                    <thead>
                        <th>#ID</th>
                        <th>Patient Progress</th>
                        <th>Created On</th>
                    </thead>
                    <tbody>
                        @foreach ($patient->followupconsultations as $note)
                            <tr>
                                <td><a href="{{route('followupnotes.show', $note->id)}}">#{{$note->id}}</a></td>
                                <td>{!! $note->patient_progress !!}</td>
                                <td>{{$note->created_at->diffForHumans()}}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                    Patient has No Follow Up Consultation Notes yet.
                @endif

      </div>
      <!-- /.tab-pane -->
      <div class="tab-pane" id="vital">
            <div class="row">
                <div class="col-md-6">
                    <strong>Vitals Note:</strong>
                </div>
                <div class="col-md-6">
                    <a href="{{route('patientvital.create', $patient->id)}}" class="btn btn-success btn-sm">Create Vitals Note</a>
                </div>
            </div>
            <hr>
            @if (count($patient->vitals) > 0)
            <table class="table table-bordered" id="vitals_table">
                <thead>
                    <th>#ID</th>
                    <th>Weight</th>
                    <th>Height</th>
                    <th>Temp</th>
                    <th>Sat O2</th>
                    <th>RR</th>
                    <th>BP</th>
                    <th>Pulse</th>
                    <th>Ad O2</th>
                    <th>Pain</th>
                    <th>Head cir.</th>
                    <th>Date</th>
                </thead>
                <tbody>
                    @foreach ($patient->vitals as $vital)
                        <tr>
                            <td><a href="{{route('vitals.show', $vital->id)}}">#{{$vital->id}}</a></td>
                            <td>{{$vital->weight}}</td>
                            <td>{{$vital->height}}</td>
                            <td>{{$vital->temperature}}</td>
                            <td>{{$vital->oxygen_saturation}}</td>
                            <td>{{$vital->respiratory_rate}}</td>
                            <td>{{$vital->systolic_bp}}-{{$vital->diastolic_bp}}</td>
                            <td>{{$vital->pulse_rate}}</td>
                            <td>{{$vital->o2_administered}}</td>
                            <td>{{$vital->pain}}</td>
                            <td>{{$vital->head_circumference}}</td>
                            <td>{{$vital->created_at->format('jS M, Y')}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @else
                Patient has No Vital Note yet.
            @endif
      </div>
      <!-- /.tab-pane -->
        <div @if(Session::has('newTemplate')) class="active tab-pane" @else class="tab-pane" @endif id="treatment-note-1">
            <div class="row">
                <div class="col-md-6">
                    <strong>Treatment Notes:</strong>
                </div>
                <div class="col-md-6">
                    <a href="{{ route('patients.newTemplate', ['id' => $patient->id, 'type'=>'note']) }}" class="btn btn-success btn-sm">Create Template</a>
                </div>
            </div>
            <hr>

                <table class="table table-bordered" id="treatment_note">
                    <thead>
                    <th>Template</th>
                    <th>Appointment</th>
                    <th>Created On</th>
                    <th>Actions</th>
                    </thead>
                    <tbody>
                    @if (count($patient->treatmentNote) > 0)
                    @foreach ($patient->treatmentNote as $note)
                        @if($note->type == 'note')
                        <tr>
                            <td><a href="{{route('patient_treatment_notes.show', $note->id)}}">{{$note->template->title}}</a></td>
                            <td>@if($note->appointment_id && isset($note->appointment->appointment_date)){{date('d M Y', strtotime($note->appointment->appointment_date))}}, {{ $note->appointment->from }}@else None @endif</td>
                            <td>{{$note->created_at->diffForHumans()}}</td>
                            <td>
                                <div class="row">
                                    <div class="col-md-4">
                                        <a href="{{route('patient_treatment_notes.show', $note->id)}}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Show</a>
                                    </div>
                                    <div class="col-md-4">
                                        <a href="{{ route('patient_treatment_notes.edit', $note->id) }}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a>
                                    </div>
                                    <div class="col-md-4">
                                        <form action="{{route('patient_treatment_notes.destroy', $note->id)}}" method="POST">
                                            {{csrf_field()}} {{method_field('DELETE')}}
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endif
                    @endforeach
                    @else
                        Patient has No Treatment Notes yet.
                    @endif
                    </tbody>
                </table>
        </div>
        <!-- /.tab-pane -->
    </div>
    <!-- /.tab-content -->
</div>
<!-- nav-tabs-custom -->
