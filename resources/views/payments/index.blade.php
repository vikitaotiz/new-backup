@extends('adminlte::page')

@section('content')

    <div class="panel panel-success" id="payments-wrapper" style="display: none">
        <div class="panel-heading">
          <div class="row">
            <div class="col-md-4">
                <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
            </div>
            <div class="col-md-4">
                <strong>All Payments</strong>
            </div>
            <div class="col-md-4">
                {{-- <a href="{{route('payments.create')}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-plus"></i> Create New payment</a> --}}
            </div>
          </div>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-1">
                    <select v-model="params.perPage" class="form-control" @change="getPayments()">
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

            <table class="table table-hover table-striped table-custom" v-if="payments.length > 0">
                <thead>
                    <tr>
                        <th @click="filterParam('sort', 'id')" :class="{ 'table-custom-header' : params.sort_by != 'id', 'sorting_asc' : params.sort_by == 'id' && params.order_by == 'asc', 'sorting_desc' : params.sort_by == 'id' && params.order_by == 'desc' }">Payment ID</th>
                        <th @click="filterParam('sort', 'invoice_id')" :class="{ 'table-custom-header' : params.sort_by != 'invoice_id', 'sorting_asc' : params.sort_by == 'invoice_id' && params.order_by == 'asc', 'sorting_desc' : params.sort_by == 'invoice_id' && params.order_by == 'desc' }">Invoice ID</th>
                        <th @click="filterParam('sort', 'product_service')" :class="{ 'table-custom-header' : params.sort_by != 'product_service', 'sorting_asc' : params.sort_by == 'product_service' && params.order_by == 'asc', 'sorting_desc' : params.sort_by == 'product_service' && params.order_by == 'desc' }">Item Name</th>
                        <th @click="filterParam('sort', 'amount')" :class="{ 'table-custom-header' : params.sort_by != 'amount', 'sorting_asc' : params.sort_by == 'amount' && params.order_by == 'asc', 'sorting_desc' : params.sort_by == 'amount' && params.order_by == 'desc' }">Amount Paid</th>
                        <th>Balance</th>
                        <th @click="filterParam('sort', 'created_at')" :class="{ 'table-custom-header' : params.sort_by != 'created_at', 'sorting_asc' : params.sort_by == 'created_at' && params.order_by == 'asc', 'sorting_desc' : params.sort_by == 'created_at' && params.order_by == 'desc' }">Paid On</th>
                        <th @click="filterParam('sort', 'doctor')" :class="{ 'table-custom-header' : params.sort_by != 'doctor', 'sorting_asc' : params.sort_by == 'doctor' && params.order_by == 'asc', 'sorting_desc' : params.sort_by == 'doctor' && params.order_by == 'desc' }">Authorized By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="payment in payments">
                        <td>@{{payment.id}}</td>
                        <td>@{{payment.invoice_id}}</td>
                        <td>@{{payment.invoice ? payment.invoice.product_service : ''}}</td>
                        <td>@{{payment.amount}}</td>
                        <td>#</td>
                        <td>@{{payment.created_at ? moment(payment.created_at).format('ddd Do, MMM, YYYY') : 'N/A'}}</td>
                        <td>@{{payment.doctor_name}}</td>
                        <td>
                            <div class="row">
                                <div class="col-md-4">
                                    <a :href="base_url + '/payments/' + payment.id" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> View</a>
                                </div>
                                <div class="col-md-8">
                                    <form :action="base_url + '/payments/' + payment.id" method="POST">
                                        {{csrf_field()}}{{method_field('DELETE')}}
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> Delete</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <h4 v-else>There are no payments</h4>

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
            el: '#payments-wrapper',
            data: {
                auth: window.auth,
                base_url: window.location.origin,
                payments: [],
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

                    _this.getPayments();
                },
                getPayments() {
                    _this = this;

                    // Submit the form using AJAX.
                    $.ajax({
                        type: 'POST',
                        url: '{{ route('payments.all') }}',
                        data: _this.params,
                        success: function(response) {
                            _this.counts = response.data.counts;
                            _this.payments = response.data.payments;
                            $('#payments-wrapper').fadeIn();
                        }
                    });
                },
                keyTyping: function(){
                    let THIS = this;
                    clearInterval(THIS.keywordTyping);
                    THIS.keywordTyping = setInterval(function () {
                        clearInterval(THIS.keywordTyping);
                        THIS.getPayments();
                    }, 1000)
                },
            },
            mounted: function () {
                this.getPayments();
            }
        })
    </script>
@stop
