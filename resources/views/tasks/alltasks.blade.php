@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                     <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i>  Back</button>
                        </div>
                        <div class="col-md-4">
                          Tasks
                        </div>
                        <div class="col-md-4">
                            {{-- <strong>{{$task->name}}</strong> --}}
                        </div>

                    </div>
                </div>


                <div class="panel-body">
                  <div class="row">

                    <div class="col-lg-4 col-xs-12">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                      <div class="inner">
                        <h3>{{$openTasks}}</h3>

                        <p>Open Tasks</p>
                      </div>
                      <a href="{{route('tasks.opentasks')}}" class="small-box-footer">
                          All Open Tasks <i class="fa fa-arrow-circle-right"></i>
                        </a>
                      </div>
                    </div>

                    <div class="col-lg-4 col-xs-12">
                    <!-- small box -->
                    <div class="small-box bg-green">
                      <div class="inner">
                        <h3>{{$closeTasks}}</h3>

                        <p>Closed Tasks</p>
                      </div>
                      <a href="{{route('tasks.closedtasks')}}" class="small-box-footer">
                          All Closed Tasks <i class="fa fa-arrow-circle-right"></i>
                        </a>
                      </div>
                    </div>

                    <div class="col-lg-4 col-xs-12">
                    <!-- small box -->
                    <div class="small-box bg-orange">
                      <div class="inner">
                        <h3>{{$tasks}}</h3>

                        <p>All Tasks</p>
                      </div>
                      <a href="{{route('tasks.index')}}" class="small-box-footer">
                          All Tasks <i class="fa fa-arrow-circle-right"></i>
                        </a>
                      </div>
                    </div>

                  </div>

                </div>
         </div>


@endsection
