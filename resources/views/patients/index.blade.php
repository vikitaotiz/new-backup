@extends('adminlte::page')

@section('content')

    <div class="panel panel-success" id="patient-wrapper">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-4">
                    <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                </div>
                <div class="col-md-4">
                    <strong>All Patients</strong>
                </div>
                <div class="col-md-4">
                    {{-- @if(!$patient->trashed()) --}}
                        <a href="{{route('patients.create')}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-user-plus"></i> Create New Patient</a>
                    {{-- @endif --}}
                </div>
            </div>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-1">
                    <select v-model="params.perPage" class="form-control" @change="getPatients()">
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <div class="col-md-5"></div>
                <div class="col-md-6">
                    <div class="pull-right">
                        <div class="form-group row">
                            <div class="col-md-6">
                                <label for="" class="pull-right">Search: </label>
                            </div>
                            <div class="col-md-6">
                                <input type="text" v-model="params.keyword" class="form-control" @change="keyTyping" @keyup="keyTyping">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <table class="table table-hover table-striped table-custom" v-if="patients.length > 0">
                <thead>
                <tr>
                    <th @click="filterParam('sort', 'nhs_number')" :class="{ 'table-custom-header' : params.sort_by != 'nhs_number', 'sorting_asc' : params.sort_by == 'nhs_number' && params.order_by == 'asc', 'sorting_desc' : params.sort_by == 'nhs_number' && params.order_by == 'desc' }">NHS Number</th>
                    <th @click="filterParam('sort', 'firstname')" :class="{ 'table-custom-header' : params.sort_by != 'firstname', 'sorting_asc' : params.sort_by == 'firstname' && params.order_by == 'asc', 'sorting_desc' : params.sort_by == 'firstname' && params.order_by == 'desc' }">Name</th>
                    <th @click="filterParam('sort', 'gender')" :class="{ 'table-custom-header' : params.sort_by != 'gender', 'sorting_asc' : params.sort_by == 'gender' && params.order_by == 'asc', 'sorting_desc' : params.sort_by == 'gender' && params.order_by == 'desc' }">Gender</th>
                    <th @click="filterParam('sort', 'date_of_birth')" :class="{ 'table-custom-header' : params.sort_by != 'date_of_birth', 'sorting_asc' : params.sort_by == 'date_of_birth' && params.order_by == 'asc', 'sorting_desc' : params.sort_by == 'date_of_birth' && params.order_by == 'desc' }">Date of Birth</th>
                    <th @click="filterParam('sort', 'created_at')" :class="{ 'table-custom-header' : params.sort_by != 'created_at', 'sorting_asc' : params.sort_by == 'created_at' && params.order_by == 'asc', 'sorting_desc' : params.sort_by == 'created_at' && params.order_by == 'desc' }">Created On</th>
                    <th>Staff Assigned</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="patient in patients">
                    <td>@{{patient.nhs_number ? patient.nhs_number : 'Not Set'}}</td>
                    <td>@{{patient.firstname}} @{{patient.lastname}}</td>
                    <td>@{{patient.gender}}</td>
                    <td>@{{patient.date_of_birth ? moment(patient.date_of_birth).format('ddd Do, MMM, YYYY') : 'N/A'}}</td>
                    <td>@{{patient.created_at ? moment(patient.created_at).format('hh:mm A ddd Do, MMM, YYYY') : 'N/A'}}</td>
                    <td>
                        <span v-if="patient.creator && patient.creator.role_id != 6">
                        @{{patient.creator.firstname ? patient.creator.firstname : 'N/A'}}
                        @{{patient.creator.lastname ? patient.creator.lastname : 'N/A'}}
                        </span>
                        <span v-else>N/A</span>
                    </td>
                    <td>
                        <div class="row">
                            <div v-if="patient.deleted_at">
                                <div class="col-md-6">
                                    <form :action="base_url + '/patients/' + patient.id" method="POST">
                                        {{csrf_field()}}{{method_field('PUT')}}
                                        <button type="submit" class="btn btn-sm btn-info" onclick="return confirm('Are you sure?')"><i class="fa fa-trash-restore"></i> Restore</button>
                                    </form>
                                </div>

                                <div class="col-md-6">
                                    <form :action="base_url + '/patients/' + patient.id" method="POST">
                                        {{csrf_field()}}{{method_field('DELETE')}}
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> Delete</button>
                                    </form>
                                </div>
                            </div>
                            <div v-else>
                                <div class="col-md-4">
                                    <a :href="base_url + '/patients/' + patient.id" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> View</a>
                                </div>
                                <div class="col-md-8">
                                    <form :action="base_url + '/patients/' + patient.id" method="POST">
                                        {{csrf_field()}}{{method_field('DELETE')}}
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> Trash</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
            <h4 v-else>There are no patients</h4>
            <div class="row">
                <div class="col-md-6">
                    <p v-if="counts && counts > params.perPage && (params.pageNo * params.perPage < counts)">Showing @{{ (params.pageNo * params.perPage) - (params.perPage - 1) }} to @{{ params.pageNo * params.perPage }} of @{{ counts }} entries</p>
                    <p v-if="counts && counts > params.perPage && (((params.pageNo * params.perPage) - (params.perPage - 1)) < counts && params.pageNo * params.perPage > counts)">Showing @{{ (params.pageNo * params.perPage) - (params.perPage - 1) }} to @{{ counts }} of @{{ counts }} entries</p>
                    <p v-if="counts && counts < params.perPage">Showing @{{ (params.pageNo * params.perPage) - (params.perPage - 1) }} to @{{ counts }} of @{{ counts }} entries</p>
                    <p v-if="counts && counts === params.perPage">Showing @{{ (params.pageNo * params.perPage) - (params.perPage - 1) }} to @{{ counts }} of @{{ counts }} entries</p>
                </div>
                <div class="col-md-6">
                    <div class="pull-right">
                        <span class="fa fa-angle-left custom-arrow" style="font-size: 25px" @click="filterParam('page', 'previous')"></span>
                        <span class="fa fa-angle-right custom-arrow" style="font-size: 25px" @click="filterParam('page', 'next')"></span>
                        <span class="count"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="{{ asset('/js/vue.min.js') }}"></script>

    <script !src="">
        new Vue({
            el: '#patient-wrapper',
            data: {
                auth: window.auth,
                base_url: window.location.origin,
                patients: [],
                counts: 0,
                params: {
                    keyword: '',
                    pageNo: 1,
                    perPage: 10,
                    sort_by: '',
                    order_by: '',
                    _token: '{{ csrf_token() }}'
                },
                keywordTyping: ''
            },
            methods: {
                filterParam(key, val) {
                    _this = this;

                    if (key == 'page') {
                        if (val == 'next') {
                            if (_this.params.pageNo * _this.params.perPage > _this.counts) {
                                return false;
                            }

                            _this.params.pageNo += 1;
                        } else {
                            if (_this.params.pageNo == 1) {
                                return false;
                            }

                            _this.params.pageNo -= 1;
                        }
                    } else if (key == 'sort') {
                        if (_this.params.order_by == '') {
                            _this.params.order_by = 'asc';
                        } else {
                            if (_this.params.order_by == 'asc') {
                                _this.params.order_by = 'desc';
                            } else {
                                _this.params.order_by = 'asc';
                            }
                        }

                        _this.params.sort_by = val;
                    }

                    _this.getPatients();
                },
                getPatients() {
                    _this = this;

                    // Submit the form using AJAX.
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('patients.all') }}',
                        data: _this.params,
                        success: function(response) {
                            _this.counts = response.data.counts;
                            _this.patients = response.data.patients;
                            $('#patient-wrapper').fadeIn();
                        }
                    });
                },
                keyTyping: function(){
                    let THIS = this;
                    clearInterval(THIS.keywordTyping);
                    THIS.keywordTyping = setInterval(function () {
                        clearInterval(THIS.keywordTyping);
                        THIS.getPatients();
                    }, 1000)
                },
            },
            mounted: function () {
                this.getPatients();
            }
        })
    </script>
@stop
