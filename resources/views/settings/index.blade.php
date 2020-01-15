@extends('adminlte::page')

@section('content')
    <div class="alert alert-success alert-dismissible" style="display: none">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <strong>Success!</strong> Excel data imported successfully.
    </div>

 <div class="panel panel-success">
    <div class="panel-heading">
           <div class="row">
                 <div class="col-md-4">
                    <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                 </div>
                  <div class="col-md-4">
                     <strong>Settings</strong>
                  </div>
               <div class="col-md-2">

               </div>
           </div>
    </div>
 </div>

  <div class="panel-body">

         @if (auth()->user()->role_id == 5)

            @include('inc.settings.patient-settings')

          @elseif (auth()->user()->role_id == 4)

            @include('inc.settings.user-settings')
            @include('inc.settings.patient-settings')

          @elseif (auth()->user()->role_id == 3 || auth()->user()->role_id == 6)

              @include('inc.settings.user-settings')
              @include('inc.settings.patient-settings')

          @elseif (auth()->user()->role_id == 2)

            @include('inc.settings.user-settings')
            @include('inc.settings.patient-settings')

        @elseif (auth()->user()->role_id == 1)

            @include('inc.settings.user-settings')
            @include('inc.settings.patient-settings')

        @endif

@endsection

