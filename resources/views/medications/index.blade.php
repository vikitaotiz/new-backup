@extends('adminlte::page')

@section('content')

    <div class="alert alert-success alert-dismissible" style="display:none">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
        <strong>Success!</strong> Imported successfully.
    </div>
    <div class="panel panel-success" id="medications-wrapper" style="display: none">
        <div class="panel-heading">
          <div class="row">
            <div class="col-md-3">
                <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
            </div>
            <div class="col-md-3">
                <strong>All Medications</strong>
            </div>
            <div class="col-md-3">
            <form method="post" enctype="multipart/form-data" action="{{route('import.medications')}}" id="file-upload">
                @csrf
                <div class="form-group">
                  <input type="file" name="select_file" class="form-control">
                  <input type="submit" value="Import" class="btn btn-sm btn-warning">
                </div>
            </form>
            </div>
            <div class="col-md-3">
                <a href="{{route('medications.create')}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-plus"></i> Create New medication</a>
            </div>
        </div>
        </div>

        <div class="panel-body">
            <div class="row">
                <div class="col-md-1">
                    <select v-model="params.perPage" class="form-control" @change="getMedications()">
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

            <table class="table table-hover table-striped table-custom" v-if="medications.length > 0">
                <thead>
                    <tr>
                        <th @click="filterParam('sort', 'name')" :class="{ 'table-custom-header' : params.sort_by != 'name', 'sorting_asc' : params.sort_by == 'name' && params.order_by == 'asc', 'sorting_desc' : params.sort_by == 'name' && params.order_by == 'desc' }">Name</th>
                        <th @click="filterParam('sort', 'created_at')" :class="{ 'table-custom-header' : params.sort_by != 'created_at', 'sorting_asc' : params.sort_by == 'created_at' && params.order_by == 'asc', 'sorting_desc' : params.sort_by == 'created_at' && params.order_by == 'desc' }">Created On</th>
                        <th @click="filterParam('sort', 'doctor')" :class="{ 'table-custom-header' : params.sort_by != 'doctor', 'sorting_asc' : params.sort_by == 'doctor' && params.order_by == 'asc', 'sorting_desc' : params.sort_by == 'doctor' && params.order_by == 'desc' }">Created By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="medication in medications">
                        <td>@{{medication.name}}</td>
                        <td>@{{medication.created_at ? moment(medication.created_at).format('ddd Do, MMM, YYYY') : 'N/A'}}</td>
                        <td>@{{medication.doctor_name}}</td>
                        <td>
                            <div class="row">
                                <div class="col-md-4">
                                    <a :href="base_url + '/medications/' + medication.id" class="btn btn-primary btn-sm"><i class="fa fa-edit"></i> View</a>
                                </div>
                                <div class="col-md-8">
                                    <form :action="base_url + '/medications/' + medication.id" method="POST">
                                        {{csrf_field()}}{{method_field('DELETE')}}
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> Delete</button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <h4 v-else>There are no medications</h4>

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

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.15.1/xlsx.full.min.js"></script>
<script src="{{ asset('/js/vue.min.js') }}"></script>
<script>
    // Send ajax request Add Product
    $("#file-upload").submit(function(event) {
        // Stop browser from submitting the form
        event.preventDefault();

        // Send ajax request
        $.ajax({
            type: 'POST',
            url: '{{ route('file.upload') }}',
            data: new FormData( this ),
            cache: false,
            contentType: false,
            processData: false,
            success: function(data){
                parseExcel(data[0], data[1]);
            },
        });
    });

    function parseExcel(uri, fileName) {
        var sheet = {
            data: []
        };

        let url = uri;
        let oReq = new XMLHttpRequest();
        oReq.open("GET", url, true);
        oReq.responseType = "arraybuffer";
        oReq.onload = function (e) {
            let arraybuffer = oReq.response;
            let data = new Uint8Array(arraybuffer);
            let arr = new Array();
            for (let i = 0; i != data.length; ++i) arr[i] = String.fromCharCode(data[i]);
            let bstr = arr.join("");
            let workbook = XLSX.read(bstr, {type: "binary"});

            sheet.data = XLSX.utils.sheet_to_json(workbook.Sheets[workbook.SheetNames[0]], {raw: true});
            uploadExcel(sheet, fileName);
        };
        oReq.send();
    }

    function uploadExcel(data, fileName) {
        // Send ajax request
        $.ajax({
            type: 'POST',
            url: '{{ route('import.medications') }}',
            data: {
                '_token': '{{ csrf_token() }}',
                'data': JSON.stringify(data),
                'fileName': fileName,
            },
            success: function(data){
                if (data.success === true) {
                    console.log(data.success)
                    $("#file-upload").trigger('reset');

                    $('.alert-success').show();
                    setTimeout(function () {
                        $('.alert-success').hide();
                        location.reload();
                    }, 1000);
                }
            },
        });
    }

    new Vue({
        el: '#medications-wrapper',
        data: {
            auth: window.auth,
            base_url: window.location.origin,
            medications: [],
            counts: 0,
            params: {
                keyword: '',
                pageNo: 1,
                perPage: 10,
                sort_by: 'name',
                order_by: 'asc',
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

                _this.getMedications();
            },
            getMedications() {
                _this = this;

                // Submit the form using AJAX.
                $.ajax({
                    type: 'POST',
                    url: '{{ route('medications.all') }}',
                    data: _this.params,
                    success: function(response) {
                        _this.counts = response.data.counts;
                        _this.medications = response.data.medications;
                        $('#medications-wrapper').fadeIn();
                    }
                });
            },
            keyTyping: function(){
                let THIS = this;
                clearInterval(THIS.keywordTyping);
                THIS.keywordTyping = setInterval(function () {
                    clearInterval(THIS.keywordTyping);
                    THIS.getMedications();
                }, 1000)
            },
        },
        mounted: function () {
            this.getMedications();
        }
    })
</script>
@endsection
