@extends('adminlte::page')

@section('title', 'Symptoms questionnaire edit')

@section('css')
    <style>
        .ml-5 {
            margin-left: 3rem !important;
        }
    </style>
@stop

@section('content')
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
                <strong>Edit Symptoms questionnaire</strong>
            </div>
            <div class="col-md-4">
            </div>
          </div>
        </div>

        <div class="panel-body">
            <form action="{{route('questionnaires.update', $questionnaire->id)}}" method="post" enctype="multipart/form-data">

                {{csrf_field()}}
                @method('put')

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Symptoms questionnaire title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" value="{{ $questionnaire->title }}" placeholder="Enter Template Name..." required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Symptoms questionnaire description</label>
                            <textarea class="form-control summernote" rows="5" name="description">{!! $questionnaire->description !!}</textarea>
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
                            <textarea class="form-control summernote" rows="5" name="generic_comment">{!! $questionnaire->generic_comment !!}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Symptoms questionnaire more info (For yes)</label>
                            <textarea class="form-control summernote" rows="5" name="yes_more_info" placeholder="more info">{!! $questionnaire->yes_more_info !!}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Symptoms questionnaire more info (No yes)</label>
                            <textarea class="form-control summernote" rows="5" name="no_more_info" placeholder="more info">{!! $questionnaire->no_more_info !!}</textarea>
                        </div>
                    </div>
                </div>

                <hr>
                <h3>Question</h3>

                <div id="section">
                    @if($questionnaire->questions)
                    @foreach($questionnaire->questions as $que)
                    <div class="panel panel-success sectionContent">
                        <input type="hidden" name="question_id[{{ $loop->index }}]" value="{{ $que->id }}">

                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label>Question Title <span class="text-danger">*</span></label>
                                        <input type="text" name="question_title[{{ $loop->index }}]" value="{{ $que->title }}" class="form-control" placeholder="Enter Question Title..." required>
                                    </div>
                                    <div class="form-group">
                                        <label>Question Description</label>
                                        <textarea name="question_description[{{ $loop->index }}]" class="form-control" placeholder="Enter Question Description...">{{ $que->description }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label>Question Image</label>
                                        <input type="file" name="question_image[{{ $loop->index }}]" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-2 text-right">
                                    <br>
                                    <button type="button" onclick="if (confirm('Are you sure want to delete this data?')) {removeDbQuestion(this, {{ $que->id }});}" class="btn btn-danger">Remove Question</button>
                                </div>
                            </div>
                        </div>

                        <div class="panel-body" id="sectionBody{{ $loop->index }}">
                            @foreach($que->answers as $ans)
                            <div class="row">
                                <input type="hidden" name="answer_id[{{ $loop->parent->index }}][{{ $loop->index }}]" value="{{ $ans->id }}">

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Answer title <span class="text-danger">*</span></label>
                                        <input type="text" name="answer_title[{{ $loop->parent->index }}][{{ $loop->index }}]" value="{{ $ans->title }}" class="form-control" placeholder="Enter Answer Title..." required>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label>Answer Advice</label>
                                        <textarea name="answer_advice[{{ $loop->parent->index }}][{{ $loop->index }}]" rows="5" class="form-control" cols="30" rows="10">{{ $ans->advice }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-2 text-right">
                                    <br>
                                    <button type="button" onclick="confirm('Are you sure want to delete this data?');removeDbAnswer(this, {{ $ans->id }})" class="btn btn-danger">Remove Answer</button>
                                </div>
                            </div>
                            @endforeach
                        </div>


                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-md-2">
                                    <button type="button" onclick="addAnswer(this, {{ $loop->index }})" class="btn btn-success">Add Answer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>

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
    </div>

@endsection

@section('js')
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
        function removeDbAnswer(_this, id) {
            $.ajax({
                type: 'post',
                url: window.location.origin + '/questionnaires/del/answer/' + id,
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'delete'
                },
                success: function (response) {
                    if (response.status == 200) {
                        $(_this).closest('.row').remove();
                    }
                }
            });
        }

        function removeDbQuestion(_this, id) {
            $.ajax({
                type: 'post',
                url: window.location.origin + '/questionnaires/del/question/' + id,
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'delete'
                },
                success: function (response) {
                    if (response.status == 200) {
                        $(_this).closest('.panel').remove();
                    }
                }
            });
        }

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
                '                                    <textarea name="question_description['+count+']" rows="5" class="form-control" placeholder="Enter Question Description..."></textarea>\n' +
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
            console.log(count, count2);
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
                '                                        <textarea name="answer_advice['+count+']['+count2+']" rows="5" class="form-control" placeholder="Enter Answer Advice..." rows="3"></textarea>\n' +
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
    </script>

@endsection
