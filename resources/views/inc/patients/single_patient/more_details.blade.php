                            <h4>More Details</h4>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <ul class="list-group list-group-unbordered">
                                                <li class="list-group-item"><strong>Gender : </strong><a>{{ucwords($patient->gender)}}</a></li>
                                                <li class="list-group-item"><strong>Height : </strong><a>{{$patient->height}} metres</a></li>
                                                <li class="list-group-item"><strong>Weight : </strong><a>{{$patient->weight}} Kgs</a></li>
                                                <li class="list-group-item"><strong>Blood Type : </strong><a>{{$patient->blood_type}}</a></li>
                                                <li class="list-group-item"><strong>Address : </strong><a>{{$patient->address}}</a></li>
                                                <li class="list-group-item"><strong>Doctor Assigned : </strong><a>#</a></li>
                                                <li class="list-group-item"><strong>Referral Source : </strong><a>{{$patient->referral_source}}</a></li>
                                            </ul>
                                        </div>
                                        <div class="col-md-8">

                                            <div class="panel panel-success">
                                                <div class="panel-heading">
                                                    General Practioner (GP) Details
                                                </div>
                                                <div class="panel-body">
                                                    {!! $patient->gp_details !!}
                                                </div>
                                            </div>

                                            <div class="panel panel-success">
                                                <div class="panel-heading">
                                                    Medication Allergies
                                                </div>
                                                <div class="panel-body">
                                                    {!! $patient->medication_allergies !!}
                                                </div>
                                            </div>

                                            <div class="panel panel-success">
                                                    <div class="panel-heading">
                                                        Current Medication
                                                    </div>
                                                    <div class="panel-body">
                                                        {!! $patient->current_medication !!}
                                                    </div>
                                                </div>

                                             <div class="panel panel-success">
                                                <div class="panel-heading">
                                                    More Information
                                                </div>
                                                <div class="panel-body">
                                                    {!! $patient->more_info !!}
                                                </div>
                                            </div>   
                                        </div>
                                    </div>