<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Product;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('products.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'code_serial_number' => 'required',
            'stock' => 'required',
            'buying_price' => 'required',
            'selling_price' => 'required',
            'product_image' => 'sometimes|file|image|max:10000'
        ]);

        $product = new Product;

        $product->name = $request->name;
        $product->code_serial_number = $request->code_serial_number;
        $product->description = $request->description;
        $product->stock = $request->stock;
        $product->buying_price = $request->buying_price;
        $product->selling_price = $request->selling_price;
        $product->user_id = auth()->user()->id;

        if($request->hasFile('product_image')) {
            $product_image = $request->product_image->store('public/product_images');
            $product_image = str_replace("public/", "", $product_image);

            $product->product_image = $product_image;

            $product->save();
        }

        $product->save();

        return redirect('products')->with('success', 'Product created succesfully');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::findOrfail($id);

        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $product = Product::findOrfail($id);

        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'code_serial_number' => 'required',
            'stock' => 'required',
            'buying_price' => 'required',
            'selling_price' => 'required',
            'product_image' => 'sometimes|file|image|max:10000'
        ]);

        $product = Product::findOrfail($id);

        if($request->hasFile('product_image')) {

            $product_image = $request->product_image->store('public/product_images');
            $product_image = str_replace("public/", "", $product_image);

            if($product->product_image) {
                Storage::delete($product->product_image);
            }

            $product->product_image = $product_image;
            $product->save();

        }

        $product->name = $request->name;
        $product->code_serial_number = $request->code_serial_number;
        $product->description = $request->description;
        $product->stock = $request->stock;
        $product->buying_price = $request->buying_price;
        $product->selling_price = $request->selling_price;

        $product->user_id = auth()->user()->id;
        $product->save();

        return redirect('products')->with('success', 'Product created succesfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrfail($id);
        if($product->product_image != 'noimage.jpg')
        {
            Storage::delete($product->product_image);
        }

        $product->delete();
        return redirect('products')->with('success', 'Product deleted succesfully');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function allProducts()
    {
        try {
            if(auth()->user() != null && isset(auth()->user()->company->company)){
                auth()->user()->company = auth()->user()->company->company;
            }

            $input = \request()->all();
            $input['keyword'] = isset($input['keyword']) ? $input['keyword'] : '';
            $pageNo = isset($input['pageNo']) && $input['pageNo'] > 0 ? $input['pageNo'] : 1;
            $limit = isset($input['perPage']) ? $input['perPage'] : 10;
            $skip = $limit * ($pageNo - 1);
            $sort_by = isset($input['sort_by']) ? $input['sort_by'] : 'id';
            $order_by = isset($input['order_by']) ? $input['order_by'] : 'desc';

            if (auth()->user()->role_id == 6) {
                $input['doctors'] = auth()->user()->company->users->pluck('id');
            } elseif (auth()->user()->role_id == 4) {
                if (count(auth()->user()->companies)) {
                    foreach (auth()->user()->companies as $company) {
                        $input['doctors'] = $company->users->pluck('id');
                    }
                } else {
                    $input['doctors'] = [];
                }
            }

            $total = Product::select('products.id')
                ->leftjoin('users AS B','B.id','=','products.user_id')
                ->where(function ($query) use ($input) {
                    if (auth()->user()->role_id == 3 || auth()->user()->role_id == 6 || auth()->user()->role_id == 4) {
                        if (isset($input['doctors'])) {
                            $query->where(function ($q) use ($input) {
                                $q->whereIn('products.user_id', $input['doctors']);
                            });
                        } else {
                            $query->where(function ($q) use ($input) {
                                $q->where('products.user_id', auth()->user()->id);
                            });
                        }
                    }
                    if (auth()->user()->role_id == 5) {
                        $query->where(function ($q) use ($input) {
                            $q->where('products.user_id', auth()->user()->id);
                        });
                    }
                    $query->where(function ($q) use ($input) {
                        $q->where('products.name', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('products.created_at', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('products.code_serial_number', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('products.stock', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('products.buying_price', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('products.selling_price', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere(\DB::raw("CONCAT(B.firstname, ' ', B.lastname)"), 'like', '%' . $input['keyword'] . '%');
                    });
                })->count();

            $sql = Product::select('products.*',
                \DB::raw("CONCAT(B.firstname, ' ', B.lastname) as doctor_name"))
                ->leftjoin('users AS B','B.id','=','products.user_id')
                ->where(function ($query) use ($input) {
                    if (auth()->user()->role_id == 3 || auth()->user()->role_id == 6 || auth()->user()->role_id == 4) {
                        if (isset($input['doctors'])) {
                            $query->where(function ($q) use ($input) {
                                $q->whereIn('products.user_id', $input['doctors']);
                            });
                        } else {
                            $query->where(function ($q) use ($input) {
                                $q->where('products.user_id', auth()->user()->id);
                            });
                        }
                    }
                    if (auth()->user()->role_id == 5) {
                        $query->where(function ($q) use ($input) {
                            $q->where('products.user_id', auth()->user()->id);
                        });
                    }
                    $query->where(function ($q) use ($input) {
                        $q->where('products.name', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('products.created_at', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('products.code_serial_number', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('products.stock', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('products.buying_price', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere('products.selling_price', 'like', '%' . $input['keyword'] . '%')
                            ->orWhere(\DB::raw("CONCAT(B.firstname, ' ', B.lastname)"), 'like', '%' . $input['keyword'] . '%');
                    });
                });

            if ($sort_by == 'doctor') {
                $sql->orderBy(\DB::raw("CONCAT(B.firstname, ' ', B.lastname)"), $order_by);
            } else {
                $sql->orderBy($sort_by, $order_by);
            }

            $products = $sql->skip($skip)->take($limit)->get();

            return response()->json(['status' => 200, 'data' => ['products' => $products, 'counts' => $total]], 200);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'errors' => $e->getMessage()], 200);
        }
    }
}
