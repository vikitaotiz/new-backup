@extends('adminlte::page')

@section('title', 'Treatment note template create')

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
                <strong>Create New Treatment Note Template</strong>
            </div>
            <div class="col-md-4">
            </div>
          </div>
        </div>

        <div class="panel-body">
            <form action="{{route('templates.store')}}" method="post">

                {{csrf_field()}}

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Template name <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control" placeholder="Enter Template Name..." required>
                        </div>
                    </div>
                </div>

                <hr>
                <h3>Template notes</h3>

                <div id="section"></div>

                <div class="row" style="padding: 1%;">
                    <div class="form-group">
                        <button type="button" onclick="addSection()" class="btn btn-success">Add section</button>
                    </div>
                </div>

                <hr>
                <h3>Print settings</h3>
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Title <span class="text-danger">*</span></label>
                            <div class="help-block">
                                If the title is left blank, Hospital-note will use the template name.
                            </div>
                            <input type="text" name="print_title" class="form-control" placeholder="Enter Title...">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Print display options</label>
                            <div class="checkbox">
                                <label><input type="checkbox" name="is_show_patients_address">Show the patient's address</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" name="is_show_patients_dob">Show the patient's date of birth</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" name="is_show_patients_nhs_number">show the practitioner/doctor name</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" name="is_show_patients_referral_source">Show the patient's reference number</label>
                            </div>
                            <div class="checkbox">
                                <label><input type="checkbox" name="is_show_patients_occupation">Show the patient's occupation</label>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row" style="padding: 1%;">
                    <div class="form-group">
                        <input type="submit" class="btn btn-success" value="Submit Template">
                    </div>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('js')

    <script>
        function addSection() {
            var count = $('#sectionContent').length;
            $('#section').append(
                '<div class="panel panel-success" id="sectionContent">\n' +
                '                    <div class="panel-heading">\n' +
                '                        <div class="row">\n' +
                '                            <div class="col-md-10">\n' +
                '                                <div class="form-group">\n' +
                '                                    <label>Section title <span class="text-danger">*</span></label>\n' +
                '                                    <input type="text" name="sections_title['+count+']" class="form-control" placeholder="Enter Section Title..." required>\n' +
                '                                </div>\n' +
                '                            </div>\n' +
                '                            <div class="col-md-2 text-right">\n' +
                '                                <br>\n' +
                '                                <button type="button" onclick="removeSection(this)" class="btn btn-danger">Remove section</button>\n' +
                '                            </div>\n' +
                '                        </div>\n' +
                '                    </div>\n' +
                '\n' +
                '                    <div class="panel-body" id="sectionBody'+count+'">\n' +
                '                        <div class="row">\n' +
                '                            <div class="col-md-5">\n' +
                '                                <div class="form-group">\n' +
                '                                    <label>Question title <span class="text-danger">*</span></label>\n' +
                '                                    <input type="text" name="question_title['+count+'][0]" class="form-control" placeholder="Enter Question Title..." required>\n' +
                '                                </div>\n' +
                '                            </div>\n' +
                '\n' +
                '                            <div class="col-md-5">\n' +
                '                                <div class="form-group">\n' +
                '                                    <label>Choose type <span class="text-danger">*</span></label>\n' +
                '                                    <select class="form-control" onchange="toogleAnswer(this, '+count+', 0)" name="type['+count+'][0]" required>\n' +
                '                                        <option label="Single line text" value="0" selected="selected">Single line text</option>\n' +
                '                                        <option label="Paragraph Text" value="1">Paragraph Text</option>\n' +
                '                                        <option label="Multiple Choice" value="2">Multiple Choice</option>\n' +
                '                                        <option label="Checkboxes" value="3">Checkboxes</option>\n' +
                '                                        <option label="Body Chart" value="4">Body Chart</option>\n' +
                '                                    </select>\n' +
                '                                </div>\n' +
                '                            </div>\n' +
                '\n' +
                '                            <div class="col-md-2 text-right">\n' +
                '                                <br>\n' +
                '                                <button type="button" onclick="removeQuestion(this)" class="btn btn-danger">Remove question</button>\n' +
                '                            </div>\n' +
                '\n' +
                '                            <div class="col-md-12">\n' +
                '                            </div>\n' +
                '                        </div>\n' +
                '                    </div>\n' +
                '\n' +
                '                    <div class="panel-footer">\n' +
                '                        <div class="row">\n' +
                '                            <div class="col-md-2">\n' +
                '                                <button type="button" onclick="addQuestion(this, '+count+')" class="btn btn-success">Add question</button>\n' +
                '                            </div>\n' +
                '                        </div>\n' +
                '                    </div>\n' +
                '                </div>'
            );
        }

        function removeSection(_this) {
            $(_this).closest('.panel').remove();
        }

        function addQuestion(_this, count) {
            var count2 = $('#sectionBody'+count+' .row').length;
            $(_this).closest('.panel-footer').siblings('.panel-body').append(
                '<div class="row">\n' +
                '                                <div class="col-md-5">\n' +
                '                                    <div class="form-group">\n' +
                '                                        <label>Question title <span class="text-danger">*</span></label>\n' +
                '                                        <input type="text" name="question_title['+count+']['+count2+']" class="form-control" placeholder="Enter Question Title..." required>\n' +
                '                                    </div>\n' +
                '                                </div>\n' +
                '\n' +
                '                                <div class="col-md-5">\n' +
                '                                    <div class="form-group">\n' +
                '                                        <label>Choose type <span class="text-danger">*</span></label>\n' +
                '                                        <select class="form-control" onchange="toogleAnswer(this, '+count+', '+count2+')" name="type['+count+']['+count2+']" required>\n' +
                '                                            <option label="Single line text" value="0" selected="selected">Single line text</option>\n' +
                '                                           <option label="Paragraph Text" value="1">Paragraph Text</option>\n' +
                '                                           <option label="Multiple Choice" value="2">Multiple Choice</option>\n' +
                '                                           <option label="Checkboxes" value="3">Checkboxes</option>\n' +
                '                                           <option label="Body Chart" value="4">Body Chart</option>\n' +
                '                                        </select>\n' +
                '                                    </div>\n' +
                '                                </div>\n' +
                '\n' +
                '                                <div class="col-md-2 text-right">\n' +
                '                                    <br>\n' +
                '                                    <button type="button" onclick="removeQuestion(this)" class="btn btn-danger">Remove question</button>\n' +
                '                                </div>\n' +
                '\n' +
                '                                <div class="col-md-12">\n' +
                '                                </div>\n' +
                '                            </div>'
            );
        }

        function removeQuestion(_this) {
            $(_this).closest('.row').remove();
        }

        function addAnswer(_this, count, count2) {
            $(_this).siblings('.answer').append(
                '<div class="row">\n' +
                '                                            <div class="col-md-1"><input type="radio" disabled></div>\n' +
                '                                            <div class="col-md-10">\n' +
                '                                                <div class="form-group"><input type="text" name="answer['+count+']['+count2+'][]" class="form-control" placeholder="Answer..."></div>\n' +
                '                                            </div>\n' +
                '                                            <div class="col-md-1">\n' +
                '                                                <button type="button" onclick="removeAnswer(this)" class="btn btn-danger"><i class="fa fa-trash"></i></button>\n' +
                '                                            </div>\n' +
                '                                        </div>'
            );
        }

        function addCheckboxAnswer(_this, count, count2) {
            $(_this).siblings('.answer').append(
                '<div class="row">\n' +
                '                                            <div class="col-md-1"><input type="checkbox" disabled></div>\n' +
                '                                            <div class="col-md-10">\n' +
                '                                                <div class="form-group"><input type="text" name="answer['+count+']['+count2+'][]" class="form-control" placeholder="Answer..."></div>\n' +
                '                                            </div>\n' +
                '                                            <div class="col-md-1">\n' +
                '                                                <button type="button" onclick="removeAnswer(this)" class="btn btn-danger"><i class="fa fa-trash"></i></button>\n' +
                '                                            </div>\n' +
                '                                        </div>'
            );
        }

        function toogleAnswer(_this, count, count2) {
            if ($(_this).val() == 1) {
                $(_this).closest('.col-md-5').siblings('.col-md-12').html(
                    '<div class="form-group ml-5">\n' +
                        '<label for="comment">Default answer </label>\n' +
                        '<textarea class="form-control" rows="5" name="answer['+count+']['+count2+'][]"></textarea>\n' +
                    '</div>'
                );
            } else if($(_this).val() == 2) {
                $(_this).closest('.col-md-5').siblings('.col-md-12').html(
                    '<div class="ml-5">\n' +
                    '                                        <label for="Answers">Answers</label>\n' +
                    '                                        <div class="answer">\n' +
                    '                                        <div class="row">\n' +
                    '                                            <div class="col-md-1"><input type="radio" disabled></div>\n' +
                    '                                            <div class="col-md-10">\n' +
                    '                                                <div class="form-group"><input type="text" name="answer['+count+']['+count2+'][]" class="form-control" placeholder="Answer..."></div>\n' +
                    '                                            </div>\n' +
                    '                                            <div class="col-md-1">\n' +
                    '                                                <button type="button" onclick="removeAnswer(this)" class="btn btn-danger"><i class="fa fa-trash"></i></button>\n' +
                    '                                            </div>\n' +
                    '                                        </div>\n' +
                    '                                        </div>\n' +
                    '                                        <button type="button" onclick="addAnswer(this, '+count+', '+count2+')" class="btn btn-success">Add answer</button>\n' +
                    '                                    </div>'
                );
            } else if($(_this).val() == 3) {
                $(_this).closest('.col-md-5').siblings('.col-md-12').html(
                    '<div class="ml-5">\n' +
                    '                                        <label for="Answers">Answers</label>\n' +
                    '                                        <div class="answer">\n' +
                    '                                        <div class="row">\n' +
                    '                                            <div class="col-md-1"><input type="checkbox" disabled></div>\n' +
                    '                                            <div class="col-md-10">\n' +
                    '                                                <div class="form-group"><input type="text" name="answer['+count+']['+count2+'][]" class="form-control" placeholder="Answer..."></div>\n' +
                    '                                            </div>\n' +
                    '                                            <div class="col-md-1">\n' +
                    '                                                <button type="button" onclick="removeAnswer(this)" class="btn btn-danger"><i class="fa fa-trash"></i></button>\n' +
                    '                                            </div>\n' +
                    '                                        </div>\n' +
                    '                                        </div>\n' +
                    '                                        <button type="button" onclick="addCheckboxAnswer(this, '+count+', '+count2+')" class="btn btn-success">Add answer</button>\n' +
                    '                                    </div>'
                );
            } else {
                $(_this).closest('.col-md-5').siblings('.col-md-12').html('');
            }
        }

        function removeAnswer(_this) {
            $(_this).closest('.row').remove();
        }
    </script>

@endsection
