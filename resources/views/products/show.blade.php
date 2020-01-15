@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                     <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>{{$product->name}}</strong>
                        </div>
                        <div class="col-md-4">
                            <a href="{{route('products.edit', $product->id)}}" class="btn btn-sm btn-primary btn-block"><i class="fa fa-pencil"></i> Edit Client</a>
                        </div>
                    </div>
                </div></div>

                <div class="">
                   
                    <div class="row">
                            <div class="col-md-3">
                    
                              <!-- Profile Image -->
                              <div class="box box-success">
                                <div class="box-body box-profile">
                                  @if($product->product_image)
                                    <img class="profile-product-img img-responsive img-square" src="{{asset('/storage/'.$product->product_image)}}" alt="product picture">
                                  @else
                                    <img class="profile-product-img img-responsive img-square" src="{{asset('img/noimage.jpg')}}" alt="product picture">
                                  @endif
                    
                                  <h3 class="profile-productname text-center">{{$product->name}}</h3>
                    
                                  <p class="text-muted text-center">Code / Serial Number: {{$product->code_serial_number ?? 'No Code / Serial Number'}}</p>
                    
                                  <ul class="list-group list-group-unbordered">
                    
                                     <li class="list-group-item"><strong>Stock : </strong><a class="pull-right">{{$product->stock}}</a></li>
                                     <li class="list-group-item"><strong>Buying Price : </strong><a class="pull-right">{{$product->buying_price}}</a></li>
                                     <li class="list-group-item"><strong>Selling Price : </strong><a class="pull-right">{{$product->selling_price}}</a></li>
                                     <li class="list-group-item"><strong>Created On : </strong><a class="pull-right">{{$product->created_at->format('D, jS, M, Y')}}</a></li>
                                     <li class="list-group-item"><strong>Created By : </strong><a class="pull-right">{{$product->user->firstname}}</a></li>
                                    
                                  </ul>
                    
                                  {{-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> --}}
                                </div>
                                <!-- /.box-body -->
                              </div>
                              <!-- /.box -->
                    
                              
                              <!-- /.box -->
                            </div>
                            <!-- /.col -->

                            <div class="col-md-9">

                              <div class="nav-tabs-custom">

                                <ul class="nav nav-tabs nav-tabs-success">
                                  <li class="active"><a href="#more_details" data-toggle="tab">More Details</a></li>
                                </ul>

                                <div class="tab-content">

                                  <div class="active tab-pane" id="more_details">

                                    <div class="panel panel-success">
                                        <div class="panel-heading">
                                            Product Description
                                        </div>
                                        <div class="panel-body">
                                            {{$product->description ?? 'No Description'}}
                                        </div>
                                    </div>
                                   
                                  </div>
                                  

                                  <!-- /.tab-pane -->
                                </div>
                                <!-- /.tab-content -->
                              </div>
                              <!-- /.nav-tabs-custom -->
                            </div>
                            <!-- /.col -->
                          </div>


                        </div>
    
@endsection
