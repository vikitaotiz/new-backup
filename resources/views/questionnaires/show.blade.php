@extends('adminlte::page')

@section('title', 'SymptomAid')

@section('content')

    <div class="panel panel-success">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-4">
                    <a href="{{ route('questionnaires.index') }}" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</a>
                </div>
                <div class="col-md-4">
                    <strong> SymptomAid Beta</strong>
                </div>
                <div class="col-md-4">

                </div>
            </div>
        </div>

        <div class="panel-body">
            <form action="{{route('questionnaires.result', $questionnaire->id)}}" method="post" enctype="multipart/form-data">

                {{csrf_field()}}

                <div class="row">
                    <div class="col-md-12 text-justify">
                        {!! nl2br($questionnaire->description) !!}
                    </div>

                    <div class="col-md-12">
                        @if (file_exists(asset('storage/public/questionnaire_image/'.$questionnaire->image)))
                            <img src="{{ asset('storage/public/questionnaire_image/'.$questionnaire->image) }}" alt="">
                        @endif
                    </div>
                </div>

                <hr>
                <h3>Please tell us more by answering below questions</h3>

                <div id="section">
                    <table class="table table-hover table-striped">
                    @if($questionnaire->questions)
                        @foreach($questionnaire->questions as $key => $que)
                            <tr>
                                <input type="hidden" name="question[{{$que->id}}]" value="{{ $que->title }}">
                                <th>{{ $que->title }}</th>
                                <td>{{ $que->description }}</td>
                                <td>
                                    @foreach($que->answers as $k => $ans)
                                        <label class="checkbox-inline">
                                            <input type="checkbox" class="answer{{ $key }}" id="answer{{ $key.'_'.$k }}" onclick="chk({{ $key }},{{ $k }})" data-title="{{ $ans->title }}" name="answer[{{$que->id}}]" value="{{ $ans->id }}" @if(strtolower($ans->title) == 'no') checked @endif> {{ $ans->title }}
                                            <input type="hidden" name="title[{{$que->id}}][{{$ans->id}}]" id="title{{ $key.'_'.$k }}" value="{{ $ans->title }}">
                                            <input type="hidden" name="advice[{{$que->id}}][{{$ans->id}}]" value="{{ $ans->advice }}">
                                        </label>
                                    @endforeach
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </table>
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
<script>
    function chk(id, k) {
        var allChk = $(".answer"+id).length;
        var myChk = $("#title"+id+'_'+k).val().toLowerCase();
        for (x=0; x<allChk; x++) {
            if (document.getElementById("answer"+id+'_'+x).checked == true) {
                var asdChk = $("#title" + id + '_' + x).val().toLowerCase();
                if (asdChk == 'yes' && myChk == 'no') {
                    $("#answer" + id + '_' + x).prop('checked', false);
                }
                if (asdChk == 'no' && myChk == 'yes') {
                    $("#answer" + id + '_' + x).prop('checked', false);
                }
            }
        }
    }
</script>
@endsection

