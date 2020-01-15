@extends('adminlte::page')

@section('content')

    <div class="panel panel-success" id="invoices-wrapper" style="display: none">
        <div class="panel-heading">
          <div class="row">
            <div class="col-md-4">
                <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
            </div>
            <div class="col-md-4">
                <strong>All Invoices</strong>
            </div>
            <div class="col-md-4">
                <a href="{{route('invoices.create')}}" class="btn btn-sm btn-success btn-block"><i class="fa fa-plus"></i> Create New invoice</a>
            </div>
        </div>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-1">
                    <select v-model="params.perPage" class="form-control" @change="getInvoices()">
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

            <table class="table table-hover table-striped table-custom" v-if="invoices.length > 0">
                <thead>
                    <tr>
                        <th @click="filterParam('sort', 'id')" :class="{ 'table-custom-header' : params.sort_by != 'id', 'sorting_asc' : params.sort_by == 'id' && params.order_by == 'asc', 'sorting_desc' : params.sort_by == 'id' && params.order_by == 'desc' }">#Invoice Number</th>
                        <th @click="filterParam('sort', 'patient')" :class="{ 'table-custom-header' : params.sort_by != 'patient', 'sorting_asc' : params.sort_by == 'patient' && params.order_by == 'asc', 'sorting_desc' : params.sort_by == 'patient' && params.order_by == 'desc' }">Patient / Invoice To</th>
                        <th @click="filterParam('sort', 'service')" :class="{ 'table-custom-header' : params.sort_by != 'service', 'sorting_asc' : params.sort_by == 'service' && params.order_by == 'asc', 'sorting_desc' : params.sort_by == 'service' && params.order_by == 'desc' }">Product / Service</th>
                        <th>Invoice Amount</th>
                        <th @click="filterParam('sort', 'created_at')" :class="{ 'table-custom-header' : params.sort_by != 'created_at', 'sorting_asc' : params.sort_by == 'created_at' && params.order_by == 'asc', 'sorting_desc' : params.sort_by == 'created_at' && params.order_by == 'desc' }">Created On</th>
                        <th @click="filterParam('sort', 'doctor')" :class="{ 'table-custom-header' : params.sort_by != 'doctor', 'sorting_asc' : params.sort_by == 'doctor' && params.order_by == 'asc', 'sorting_desc' : params.sort_by == 'doctor' && params.order_by == 'desc' }">Created By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="invoice in invoices">
                        <td>#00HN - @{{invoice.id}}</td>
                        <td>
                            @{{ invoice.patient_name ? invoice.patient_name : invoice.insurance_name }}
                        </td>
                        <td>@{{invoice.service_name ? invoice.service_name : 'no service'}}</td>
                        <td>
                            @{{(invoice.quantity) * (invoice.charge ? invoice.charge.amount : 0)}}
                            (@{{invoice.currency ? invoice.currency.symbol : '$'}})
                        </td>
                        <td>@{{invoice.created_at ? moment(invoice.created_at).format('ddd Do, MMM, YYYY') : 'N/A'}}</td>
                        <td>@{{invoice.doctor_name}}</td>
                        <td>
                            <div class="row">
                                <div class="col-md-4">
                                    <a :href="base_url + '/invoices/' + invoice.id" class="btn btn-primary btn-sm">View</a>
                                </div>
                                <div class="col-md-8">
                                    <form :action="base_url + '/invoices/' + invoice.id" method="POST">
                                        {{csrf_field()}}{{method_field('DELETE')}}
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <h4 v-else>There are no invoices</h4>

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
            el: '#invoices-wrapper',
            data: {
                auth: window.auth,
                base_url: window.location.origin,
                invoices: [],
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

                    _this.getInvoices();
                },
                getInvoices() {
                    _this = this;

                    // Submit the form using AJAX.
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('invoices.all') }}',
                        data: _this.params,
                        success: function(response) {
                            _this.counts = response.data.counts;
                            _this.invoices = response.data.invoices;
                            $('#invoices-wrapper').fadeIn();
                        }
                    });
                },
                keyTyping: function(){
                    let THIS = this;
                    clearInterval(THIS.keywordTyping);
                    THIS.keywordTyping = setInterval(function () {
                        clearInterval(THIS.keywordTyping);
                        THIS.getInvoices();
                    }, 1000)
                },
            },
            mounted: function () {
                this.getInvoices();
            }
        })
    </script>
@stop
