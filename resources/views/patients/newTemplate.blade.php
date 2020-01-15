@extends('adminlte::page')

@section('css')
<style>
span.select2.select2-container.select2-container--default {
    width: 100% !important;
}
.canvas-container {
    margin:auto
}
.body-chart-holder {
    width:250px; 
    border: 1px solid #dddddd; 
    float: left; 
    position:relative
}
.body-chart-delete-btn {
    position: absolute;
    top: 5px;
    right: 5px;
    border: 1px solid #d2d2d2;
    cursor: pointer;
    border-radius: 15px;
    width: 30px;
    height: 30px;
    padding: 4px;
    text-align: center;
    box-shadow: 2px 2px #d2d2d2;
}
.body-chart-delete-btn:hover{
    background-color: #cb4141;
    color: #fff;
}
</style>
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="{{ asset('css/select2.min.css') }}" rel="stylesheet" />
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
@include('inc.tabMenu', ['tabMenuPosition'=> $type == 'note' ? 5 : 7, 'patient_id'=>$patient->id])
<div class="row" id="newTemplate">
    <div class="col-md-12">
        <div class="box box-success">            
            <div class="box-body">
                <form action="{{route('patient_treatment_notes.store')}}" id="create-template-form" method="post">

                    {{csrf_field()}}
                    <input type="hidden" name="user_id" value="{{ $patient->id }}">
                    <input type="hidden" name="type" value="{{ $type }}">

                    <div class="row" style="padding: 1%;">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Select Template</label>
                                <select name="template_id" id="template_id" @change="getTemplate()" class="template_id form-control" required>
                                    <option selected disabled value="">Select Template</option>
                                    @foreach ($templates as $template)
                                        <option value="{{$template->id}}">{{$template->title}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Select Appointment</label>
                                <select name="appointment_id" id="appointment_id" class="form-control">
                                    <option value="">None</option>
                                    @foreach ($patient->appointments as $appointment)
                                        <option value="{{$appointment->id}}">{{date('d M Y', strtotime($appointment->appointment_date))}}, {{ $appointment->from }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div v-if="!edit" v-for="section in sections">
                                <h4>@{{ section.title }}</h4>
                                <div class="row">
                                    <div v-for="question in section.questions" class="col-md-12">
                                        <div v-if="question.type == 0" class="form-group">
                                            <label for="text">@{{ question.title }}</label>
                                            <input type="text" :name="'answer[' + section.id + '][' + question.id + ']'" class="form-control" placeholder="Answer" required>
                                        </div>
        
                                        <div v-if="question.type == 1" class="form-group">
                                            <label for="comment">@{{ question.title }} </label>
                                            <textarea class="form-control" rows="5" v-if="question.answers.length == 0" :name="'answer[' + section.id + '][' + question.id + ']'" placeholder="Answer" required></textarea>
                                            <textarea class="form-control" rows="5" v-else="" :name="'answer[' + section.id + '][' + question.id + ']'" placeholder="Answer" required>@{{ question.answers[0].answer }}</textarea>
                                        </div>
        
                                        <div v-if="question.type == 2" class="form-group">
                                            <label for="text">@{{ question.title }}</label>
                                            <br>
                                            <label v-for="answer in question.answers" class="radio-inline">
                                                <input type="radio" :value="answer.answer" :name="'answer[' + section.id + '][' + question.id + ']'" required>@{{ answer.answer }}
                                            </label>
                                        </div>
        
                                        <div v-if="question.type == 3" class="form-group">
                                            <label for="comment">@{{ question.title }} </label>
                                            <br>
                                            <label v-for="answer in question.answers" class="checkbox-inline">
                                                <input type="checkbox" :value="answer.answer" :name="'answer[' + section.id + '][' + question.id + '][]'">@{{ answer.answer }}
                                            </label>
                                        </div>
        
                                        <div v-if="question.type == 4" class="form-group">
                                            <label for="comment">@{{ question.title }} </label>   
                                            <div class="row">          
                                                <input type="hidden" :value="bodycharts[question.id].join(',')" :name="'answer[' + section.id + '][' + question.id + '][]'" v-if="bodycharts[question.id]"> 
                                                <input type="hidden" :value="''" :name="'answer[' + section.id + '][' + question.id + '][]'" v-else>
                                                <div v-for="answer in bodycharts[question.id]" class="body-chart-holder">
                                                    <span class="body-chart-delete-btn"><i class="fa fa-trash" @click="removeBodyChart(question.id, answer)"></i></span>
                                                    <img :src="'{{asset('/')}}'+answer" style="width:100%">
                                                </div>  
                                            </div>   
                                            <br>                                    
                                            <a href="javascript:void(0)" class="btn btn-info btn-sm" @click="addNewBodyCanvas(question.id)"><i class="fa fa-plus"></i> Add another body chart on @{{ question.title }}</a>
                                        </div>
                                    </div>
                                </div>
                                <hr>
                                <button type="submit" class="btn btn-success btn-sm" >Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="bodySelect" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Body chart</h4>
                </div>

                <div class="modal-body">
                    <div class="row" v-if="showBodyCanvas">
                        <canvas width="600" height="600" id="bodyCanvas"></canvas>
                    </div>
                    <div class="row" v-if="!showBodyCanvas">
                        <div class="col-sm-12 col-md-3 col-xl-3" style="height: 170px; border: 1px solid #dddddd" v-for="bodychart in storedBodycharts">
                            <a href="javascript:void(0)" @click="bodyCanvasShow('storage/'+bodychart.link)">
                                <img :src="'{{asset('/storage')}}/'+bodychart.link" style="width:100%; max-height: 100%">
                            </a>
                        </div>
                    </div>
                    
                    <hr>
                    <div class="row" style="padding: 1%;">
                        <div class="col-md-4">
                            <a href="javascript:void(0)" class="btn btn-default btn-block" @click="clearBodyCanvas()">Change body chart</a>
                        </div>
                        <div class="col-md-4">
                            <a href="javascript:void(0)" class="btn btn-default btn-block" data-dismiss="modal">Close</a>
                        </div>
                        <div class="col-md-4">
                                <a href="javascript:void(0)" class="btn btn-default btn-block" @click="importImage()">Add body chart</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('js/select2.min.js') }}"></script>
<script src="{{ asset('js/vue.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/3.4.0/fabric.min.js"></script>
<script>
    new Vue({
        el: '#newTemplate',
        data: {
            edit: false,
            template: null,
            appointment: null,
            showBodyCanvas: false,
            sections: [],
            questions: [],
            answers: [],
            bodycharts: [],
            storedBodycharts: [],
            questionId: null,
            canvas: null
        },
        methods: {
            renderSelect(){
                var selectDropDown = $(".template_id").select2();
                selectDropDown.on('select2:select', function (e) {
                    var event = new Event('change');
                    e.target.dispatchEvent(event);
                });
            },
            getTemplate() {
                let _this = this;

                $.ajax({
                    type: 'get',
                    url: window.location.origin + '/templates/' + $('#template_id option:selected').val(),
                    success: function (response) {
                        if (response.status == 200) {
                            _this.sections = response.data.sections;
                            _this.questions = response.data.sections.questions;
                            for(var i = 0; i < _this.sections.length; i++) {
                                var questions = _this.sections[i]['questions'];
                                for(var j = 0; j < questions.length; j++) {
                                    if(questions[j].type == 4)
                                    _this.bodycharts[questions[j].id] = []; 
                                }
                            }
                        }
                    }
                });
            },
            getBodycharts() {
                let _this = this;
                $.ajax({
                    type: 'get',
                    url: '{{ route('getBodycharts') }}',
                    success: function (response) {
                        _this.storedBodycharts = response.bodycharts;
                    }
                });
            },
            removeBodyChart(questionId, answer) {
                const index = this.bodycharts[questionId].indexOf(answer);
                if (index => 0)
                    this.bodycharts[questionId].splice(index, 1);
            },
            addNewBodyCanvas(questionId) {
                this.questionId = questionId;
                this.clearBodyCanvas();
                $('#bodySelect').modal('show');
            },
            bodyCanvasShow(link) {
                var that = this;
                this.$set(this, 'showBodyCanvas', true);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    type: 'post',
                    url: '{{ route('pngToBase64') }}',
                    data: { link: link },
                    success: function (response) {
                        if (response.success == true) {
                            that.canvas = new fabric.Canvas('bodyCanvas');
                            that.canvas.hoverCursor = 'pointer';
                            that.canvas.freeDrawingBrush.color = '#2192ff';
                            that.canvas.isDrawingMode = true;
                            that.canvas.freeDrawingBrush.width = 5;
                            let imgObj = new Image();
                            imgObj.src = 'data:image/png;base64,'+response.image64;
                            imgObj.onload = function () {
                                let image = new fabric.Image(imgObj);
                                image.set({
                                    angle: 0,
                                    selectable: false
                                }).scaleToWidth(500);
                                that.canvas.centerObject(image);
                                that.canvas.add(image);
                                that.canvas.renderAll();                                    
                            }
                        }
                    }
                });
            },
            clearBodyCanvas() {
                if (this.canvas) {
                    this.canvas.dispose();
                }
                this.$set(this, 'showBodyCanvas', false);
            },
            importImage() {
                    let canvas = this.canvas;
                    let that = this;
                    let imgData = canvas.toDataURL({
                        format: 'png',
                        quality: 1,
                        enableRetinaScaling: 1,
                        multiplier: 1,
                    });
                    imgData = imgData.replace('data:image/png;base64,', '');
                    let param = {
                        imgData: imgData,
                        media_type: 1,
                    };
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '{{ route('base64ToPng') }}',
                        data: param,
                        type: 'post',
                        success: function (response) { 
                            if (response.success) {                        
                                $('#bodySelect').modal('hide');
                                var bodycharts = that.bodycharts[that.questionId];
                                if (bodycharts === undefined) bodycharts = [];
                                bodycharts.push(response.imageSrc);
                                that.$set(that.bodycharts, that.questionId, bodycharts);
                            }
                        }
                    });
                },
        }, 
        mounted: function () {
            this.renderSelect();
            this.getBodycharts();
        }
    });


</script>


@stop