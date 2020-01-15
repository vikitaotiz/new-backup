@extends('adminlte::page')

@section('content')

            <div class="panel panel-success">
                <div class="panel-heading">
                      <div class="row">
                        <div class="col-md-4">
                            <button onclick="goBack()" class="btn btn-sm btn-success"><i class="fa fa-arrow-left"></i> Back</button>
                        </div>
                        <div class="col-md-4">
                            <strong>Edit Product</strong>
                        </div>
                        <div class="col-md-4">
                            {{-- <a href="{{route('products.create')}}" class="btn btn-sm btn-primary">Create New product</a> --}}
                        </div>
                    </div>
                </div>

                <div class="panel-body">
  
                        <form action="{{route('products.update', $product->id)}}" method="post" enctype="multipart/form-data">
                                {{csrf_field()}} {{method_field('PUT')}}
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Product Name</label>
                                            <input type="text" name="name" value="{{$product->name}}" class="form-control" placeholder="Enter Product Name...">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                            <div class="form-group">
                                                <label>Product Code / Serial Number</label>
                                                <input type="text" name="code_serial_number" value="{{$product->code_serial_number}}" class="form-control" placeholder="Enter Product Code / Serial Number...">
                                            </div>
                                        </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Product Image</label>
                                            <input type="file" name="product_image" class="form-control" value="{{$product->product_image}}" accept="image/jpeg,image/gif,image/png,image/x-eps">
                                        </div>
                                    </div>
                                </div><hr>
                                
                                <div class="row" style="padding: 2%;">
                                    <div class="form-group">
                                        <label>Product Description</label>
                                        <textarea name="description" class="form-control" placeholder="Enter Product Description...">{{$product->description}}</textarea>
                                    </div>
                                </div><hr>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Product Stock</label>
                                            <input type="number" name="stock" value="{{$product->stock}}" class="form-control" placeholder="Enter Product Stock...">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Buying Price Per Item</label>
                                            <input type="text" name="buying_price" value="{{$product->buying_price}}" class="form-control" placeholder="Enter Product Buying Price...">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Selling Price Per Item</label>
                                            <input type="text" name="selling_price" value="{{$product->selling_price}}" class="form-control" placeholder="Enter Product Selling Price...">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row" style="padding: 2%;">
                                    <div class="form-group" >
                                        <input type="submit" value="Submit product" class="btn btn-sm btn-block btn-success">
                                    </div>
                                </div>
                                
                            </form>
                </div>
            </div>
    
@endsection
