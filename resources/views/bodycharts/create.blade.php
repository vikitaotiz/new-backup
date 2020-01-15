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
                <strong>Create new body chart</strong>
            </div>
            <div class="col-md-4">
            </div>
          </div>
        </div>
        <div class="panel-body">
            <h2>Add new chart</h2>
            <form action="{{route('bodycharts.store')}}" method="POST" enctype="multipart/form-data">
                {{csrf_field()}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Body chart <span class="text-danger">*</span></label>
                            <input type="file" accept=".png" name="image" class="form-control" required>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-success"> Submit</button>
            </form>
        </div>
    </div>

@endsection