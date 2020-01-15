<h4 class="text-center">Patient's Letters</h4><hr>
<!-- Custom Tabs -->
<div class="nav-tabs-custom">
  <ul class="nav nav-tabs">
      <li class="active"><a href="#treatment-note" data-toggle="tab">Template</a></li>
    {{--<li><a href="#referral" data-toggle="tab">Referral Letters</a></li>
    <li><a href="#sicknote" data-toggle="tab">Sick Note Letter</a></li>
    <li><a href="#certificate" data-toggle="tab">Certificate Letters</a></li>--}}
  </ul>
  <div class="tab-content">
      <div class="tab-pane active" id="treatment-note">
          <div class="row">
              <div class="col-md-6">
                  <strong>Treatment Notes:</strong>
              </div>
              <div class="col-md-6">
                  <a href="{{ route('patients.newTemplate', ['id' => $patient->id, 'type'=>'letter']) }}" data-toggle="modal" class="btn btn-success btn-sm">Create Template</a>
              </div>
          </div>
          <hr>

          @if (count($patient->treatmentNote) > 0)
              <table class="table table-bordered">
                  <thead>
                  <th>Template</th>
                  <th>Appointment</th>
                  <th>Created On</th>
                  <th>Actions</th>
                  </thead>
                  <tbody>
                  @foreach ($patient->treatmentNote as $note)
                    @if($note->type == 'letter')
                      <tr>
                          <td><a href="{{route('patient_treatment_notes.show', $note->id)}}">{{$note->template->title}}</a></td>
                          <td>@if($note->appointment_id){{date('d M Y', strtotime($note->appointment->appointment_date))}}, {{ $note->appointment->from }}@else None @endif</td>
                          <td>{{$note->created_at->diffForHumans()}}</td>
                          <td>
                              <div class="row">
                                  <div class="col-md-4">
                                      <a href="{{route('patient_treatment_notes.show', $note->id)}}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Show</a>
                                  </div>
                                  <div class="col-md-4">
                                      <a @click="editNote('{{ $note->id }}', '{{ route('patient_treatment_notes.update', $note->id) }}')" href="{{ route('patient_treatment_notes.edit', $note->id) }}" data-toggle="modal" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a>
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
                  </tbody>
              </table>
          @else
              Patient has No Treatment Notes yet.
          @endif
      </div>

    {{--<div class="tab-pane" id="referral">
            <div class="row">
                <div class="col-md-6">
                    <strong>Referral Letters:</strong>
                </div>
                <div class="col-md-6">
                    <a href="{{route('patientreferral.create', $patient->id)}}" class="btn btn-success btn-sm">Create Referral Letter</a>
                </div>
            </div>
            <hr>

      @if (count($patient->referrals) > 0)
            <table class="table table-bordered" id="referrals_table">
                <thead>
                    <th>#ID</th>
                    <th>Referral Letter Name</th>
                    <th>Created On</th>
                </thead>
                <tbody>
                    @foreach ($patient->referrals as $referral)
                        <tr>
                            <td>{{$referral->id}}</td>
                            <td><a href="{{route('referrals.show', $referral->id)}}">{{$referral->name}}</a></td>
                            <td>{{$referral->created_at->diffForHumans()}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            Patient has No Referral Letters yet.
        @endif

    </div>
    <!-- /.tab-pane -->
    <div class="tab-pane" id="sicknote">
            <div class="row">
                    <div class="col-md-6">
                        <strong>Sick Note Letters:</strong>
                    </div>
                    <div class="col-md-6">
                        <a href="{{route('patientsicknote.create', $patient->id)}}" class="btn btn-success btn-sm">Create Sick Note Letter</a>
                    </div>
            </div>
            <hr>

            @if (count($patient->sicknotes) > 0)
                  <table class="table table-bordered" id="sicknotes_table">
                      <thead>
                          <th>#ID</th>
                          <th>Sick Note Letter Name</th>
                          <th>Created On</th>
                      </thead>
                      <tbody>
                          @foreach ($patient->sicknotes as $note)
                              <tr>
                                  <td>{{$note->id}}</td>
                                  <td><a href="{{route('sicknotes.show', $note->id)}}">{{$note->name}}</a></td>
                                  <td>{{$note->created_at->diffForHumans()}}</td>
                              </tr>
                          @endforeach
                      </tbody>
                  </table>
              @else
                  Patient has No Sick Note Letters yet.
              @endif

    </div>
    <!-- /.tab-pane -->
    <div class="tab-pane" id="certificate">
            <div class="row">
                    <div class="col-md-6">
                        <strong>Certificate Letters:</strong>
                    </div>
                    <div class="col-md-6">
                        <a href="{{route('patientcertificate.create', $patient->id)}}" class="btn btn-success btn-sm">Create Cerificate Letter</a>
                    </div>
            </div>
            <hr>

            @if (count($patient->certificates) > 0)
                  <table class="table table-bordered" id="certificates_table">
                      <thead>
                          <th>#ID</th>
                          <th>Certificate Letter Name</th>
                          <th>Created On</th>
                      </thead>
                      <tbody>
                          @foreach ($patient->certificates as $certificate)
                              <tr>
                                  <td>{{$certificate->id}}</td>
                                  <td><a href="{{route('certificates.show', $certificate->id)}}">{{$certificate->name}}</a></td>
                                  <td>{{$certificate->created_at->diffForHumans()}}</td>
                              </tr>
                          @endforeach
                      </tbody>
                  </table>
              @else
                  Patient has No Certificate Letters yet.
              @endif

    </div>
    <!-- /.tab-pane -->--}}
  </div>
  <!-- /.tab-content -->
</div>
<!-- nav-tabs-custom -->
