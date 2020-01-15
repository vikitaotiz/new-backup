@extends('adminlte::page')

@section('title', 'Symptoms questionnaire result')

@section('content')

    <div class="panel panel-success">
        <div class="panel-heading">
            <div class="row">
                <div class="col-md-4">
                    <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                </div>
                <div class="col-md-4">
                    <strong>Results</strong>
                </div>
                <div class="col-md-4">

                </div>
            </div>
        </div>

        <div class="panel-body">
            @php($yesAnswer = 0)
            @if($generic)
                <div class="alert alert-info">
                    {!! $questionnaire->generic_comment !!}<br><br>
                </div>
            @else
                <table class="table table-striped table-hover">
                    @foreach($request->answer as $index => $answer)
                        @if(strtolower($request->title[$index][$answer]) == 'yes')
                        @php($yesAnswer++)
                        <tr>
                            <th>{{ $request->question[$index] }}</th>
                            <td style="word-break: break-all">{!! nl2br($request->advice[$index][$answer]) !!}</td>
                        </tr>
                        @endif
                    @endforeach
                </table>
            @endif
            @if($yesAnswer > 0)
            <button onclick="document.getElementById('yes_more_info').style.display = 'block';this.style.display = 'none';" class="btn btn-default">Click here for more info</button>
            <div style="display: none" id="yes_more_info">
                <h2>More info</h2>
                {!! $questionnaire->yes_more_info !!}
            </div>
            @else
            <button onclick="document.getElementById('no_more_info').style.display = 'block';this.style.display = 'none';" class="btn btn-default">Click here for more info</button>
            <div style="display: none" id="no_more_info">
                <h2>More info</h2>
                {!! $questionnaire->no_more_info !!}
            </div>
            @endif
        </div>
    </div>

@endsection

