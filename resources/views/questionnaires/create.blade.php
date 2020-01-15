@extends('adminlte::page')

@section('title', 'Symptoms questionnaire create')

@section('css')
    <style>
        .ml-5 {
            margin-left: 3rem !important;
        }
        textarea{
            resize: vertical
        }
    </style>
@stop

@section('content')
    <div class="alert alert-success alert-dismissible" style="display: none">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Success!</strong> Excel data imported successfully.
    </div>

    @if(Session::has('success'))
        <div class="alert alert-success alert-dismissible">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Success!</strong> {{ Session::get('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="panel panel-success">
        <div class="panel-heading">
          <div class="row">
            <div class="col-md-4">
                <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
            </div>
            <div class="col-md-4">
                <strong>Create Symptoms questionnaire</strong>
            </div>
            <div class="col-md-4">
            </div>
          </div>
        </div>

        <div class="panel-body">
            <form action="{{route('questionnaires.store')}}" method="post" enctype="multipart/form-data">

                {{csrf_field()}}

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Symptoms questionnaire title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="Enter Symptoms questionnaire..." required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Symptoms questionnaire description</label>
                            <textarea class="form-control summernote" rows="5" name="description"></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Symptoms questionnaire image</label>
                            <input type="file" accept="image/*" name="image" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Symptoms questionnaire generic advice</label>
                            <textarea class="form-control summernote" rows="5" name="generic_comment"></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Symptoms questionnaire more info (For yes)</label>
                            <textarea class="form-control summernote" rows="5" name="yes_more_info" placeholder="more info"></textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Symptoms questionnaire more info (For no)</label>
                            <textarea class="form-control summernote" rows="5" name="no_more_info" placeholder="more info"></textarea>
                        </div>
                    </div>
                </div>

                <hr>
                <h3>Question</h3>

                <div id="section"></div>

                <div class="row" style="padding: 1%;">
                    <div class="form-group">
                        <button type="button" onclick="addQuestion()" class="btn btn-success">Add Question</button>
                    </div>
                </div>

                <hr>

                <div class="row" style="padding: 1%;">
                    <div class="form-group">
                        <input type="submit" class="btn btn-success" value="Submit">
                    </div>
                </div>
            </form>
        </div>

        <hr>
        <div class="panel-body">
            <h2>Create using excel file</h2>
            <form action="{{route('questionnaires.import')}}" method="POST" id="excel-import">
                {{csrf_field()}}

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Excel file <span class="text-danger">*</span></label>
                            <input type="file" accept=".csv,.xlsx,.xls" name="excel_file" class="form-control" required>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-success"> Submit</button>
            </form>
        </div>
    </div>

@endsection

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.15.1/xlsx.full.min.js"></script>
    <link rel="stylesheet" href="{{ asset('/summernote/summernote.css') }}">
    <script src="{{ asset('/summernote/summernote.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.summernote').summernote({
                toolbar: [
                    ['style', ['bold', 'italic', 'underline', 'clear']],
                    ['fontsize', ['fontsize']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']]
                ]
            });
        });
    </script>

    <script>
        function addQuestion() {
            var count = $('.sectionContent').length;
            $('#section').append(
                '<div class="panel panel-success sectionContent">\n' +
                '                    <div class="panel-heading">\n' +
                '                        <div class="row">\n' +
                '                            <div class="col-md-10">\n' +
                '                                <div class="form-group">\n' +
                '                                    <label>Question Title <span class="text-danger">*</span></label>\n' +
                '                                    <input type="text" name="question_title['+count+']" class="form-control" placeholder="Enter Question Title..." required>\n' +
                '                                </div>\n' +
                '                                <div class="form-group">\n' +
                '                                    <label>Question Description</label>\n' +
                '                                    <textarea name="question_description['+count+']" rows="5" class="form-control" placeholder="Enter Question Description..." rows="4"></textarea>\n' +
                '                                </div>\n' +
                '                                <div class="form-group">\n' +
                '                                    <label>Question Image</label>\n' +
                '                                    <input type="file" name="question_image['+count+']" class="form-control">\n' +
                '                                </div>\n' +
                '                            </div>\n' +
                '                            <div class="col-md-2 text-right">\n' +
                '                                <br>\n' +
                '                                <button type="button" onclick="removeQuestion(this)" class="btn btn-danger">Remove Question</button>\n' +
                '                            </div>\n' +
                '                        </div>\n' +
                '                    </div>\n' +
                '\n' +
                '                    <div class="panel-body" id="sectionBody'+count+'">\n' +
                '                        <div class="row">\n' +
                '                            <div class="col-md-3">\n' +
                '                                <div class="form-group">\n' +
                '                                    <label>Answer Title <span class="text-danger">*</span></label>\n' +
                '                                    <input type="text" name="answer_title['+count+'][0]" class="form-control" placeholder="Enter Answer Title..." value="Yes" required>\n' +
                '                                </div>\n' +
                '                            </div>\n' +
                '\n' +
                '                            <div class="col-md-7">\n' +
                '                                <div class="form-group">\n' +
                '                                    <label>Answer Advice</label>\n' +
                '                                    <textarea name="answer_advice['+count+'][0]" rows="5" class="form-control" placeholder="Enter Answer Advice..." rows="3"></textarea>\n' +
                '                                </div>\n' +
                '                            </div>\n' +
                '\n' +
                '                            <div class="col-md-2 text-right">\n' +
                '                                <br>\n' +
                '                                <button type="button" onclick="removeAnswer(this)" class="btn btn-danger">Remove Answer</button>\n' +
                '                            </div>\n' +
                '                        </div>\n' +
                '                    </div>\n' +
                '\n' +
                '                    <div class="panel-footer">\n' +
                '                        <div class="row">\n' +
                '                            <div class="col-md-2">\n' +
                '                                <button type="button" onclick="addAnswer(this, '+count+')" class="btn btn-success">Add Answer</button>\n' +
                '                            </div>\n' +
                '                        </div>\n' +
                '                    </div>\n' +
                '                </div>'
            );
        }

        function removeQuestion(_this) {
            $(_this).closest('.panel').remove();
        }

        function addAnswer(_this, count) {
            var count2 = $('#sectionBody'+count+' .row').length;
            var ansVal = (count2==1)?'No':'';
            $(_this).closest('.panel-footer').siblings('.panel-body').append(
                '<div class="row">\n' +
                '                                <div class="col-md-3">\n' +
                '                                    <div class="form-group">\n' +
                '                                        <label>Answer Title <span class="text-danger">*</span></label>\n' +
                '                                        <input type="text" name="answer_title['+count+']['+count2+']" class="form-control" placeholder="Enter Answer Title..." value="'+ansVal+'" required>\n' +
                '                                    </div>\n' +
                '                                </div>\n' +
                '\n' +
                '                                <div class="col-md-7">\n' +
                '                                    <div class="form-group">\n' +
                '                                        <label>Answer Advice</label>\n' +
                '                                       <textarea name="answer_advice['+count+']['+count2+']" rows="5" class="form-control" placeholder="Enter Answer Advice..." rows="3"></textarea>\n' +
                '                                    </div>\n' +
                '                                </div>\n' +
                '\n' +
                '                                <div class="col-md-2 text-right">\n' +
                '                                    <br>\n' +
                '                                    <button type="button" onclick="removeAnswer(this)" class="btn btn-danger">Remove Answer</button>\n' +
                '                                </div>\n' +
                '                            </div>'
            );
        }

        function removeAnswer(_this) {
            $(_this).closest('.row').remove();
        }

        // Send ajax request
        $("#excel-import").submit(function(event) {
            // Stop browser from submitting the form
            event.preventDefault();

            // Send ajax request
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
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
                Questionnaires: [],
                Questions: [],
                Answers: [],
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

                $.each(workbook.SheetNames, function (i, v) {
                    if(v === "Questionnaires"){
                        sheet.Questionnaires = XLSX.utils.sheet_to_json(workbook.Sheets[workbook.SheetNames[i]], {raw: true});
                    }
                    else if(v === "Questions"){
                        sheet.Questions = XLSX.utils.sheet_to_json(workbook.Sheets[workbook.SheetNames[i]], {raw: true});
                    }
                    else if(v === "Answers"){
                        sheet.Answers = XLSX.utils.sheet_to_json(workbook.Sheets[workbook.SheetNames[i]], {raw: true});
                    }
                });

                uploadExcel(sheet, fileName);
            };
            oReq.send();
        }

        function uploadExcel(data, fileName) {
            // Send ajax request
            $.ajax({
                type: 'POST',
                url: '{{ route('questionnaires.import.store') }}',
                data: {
                    '_token': '{{ csrf_token() }}',
                    'data': JSON.stringify(data),
                    'fileName': fileName,
                },
                success: function(data){
                    if (data.status === 200) {
                        $("#excel-import").trigger('reset');

                        $('.alert-success').show();
                        setTimeout(function () {
                            $('.alert-success').hide();
                        }, 3000);
                    }
                },
            });
        }
    </script>

@endsection
