<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
use Illuminate\Http\Request;
use App\Http\Resources\Product as ProductResource;
use App\Http\Resources\Category as CategoryResource;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageSize = $request->page_size ?? 10;
        $products = Product::orderby('id', 'desc')->paginate($pageSize);
        $categories = Category::orderby('id', 'desc')->paginate($pageSize);

        // yo chai pagination with multiple data ko lagi
        return [
            'categories' => CategoryResource::collection($categories)->response()->getData(true),
            'products' => ProductResource::collection($products)->response()->getData(true),
        ];
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|unique:products,product_name',
            'product_image' => 'image|mimes:jpeg,png,jpg,gif,svg',
            'category_id' => 'required|integer',
            'product_cost' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Error Validation', $validator->errors(), 400);
        }
        $product_name = $request->input('product_name');
        $slug = Str::slug($product_name);
        $category_id = $request->input('category_id');
        $product_description = $request->input('product_description');
        $product_image = $request->file('product_image');
        $product_cost = $request->input('product_cost');
        if ($product_image) {
            $uniquename = md5(time());
            $extension = $product_image->getClientOriginalExtension();
            $image_name = $uniquename . '.' . $extension;
            $product_image->move('site/uploads/product/', $image_name);
        }
        $product = new Product();
        $product->product_name = $product_name;
        $product->slug = $slug;
        $product->category_id = $category_id;
        $product->product_description = $product_description;
        $product->product_cost = $product_cost;
        if ($product_image) {
            $product->product_image = $image_name;
        }
        $product->save();
        return $this->sendResponse(new ProductResource($product), ' product added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $product = Product::find($id);
        if (is_null($product)) {
            return $this->sendError('products does not exist.');
        }
        return $this->sendResponse(new ProductResource($product), 'Single products fetched.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //


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
        $validator = Validator::make($request->all(), [
            'product_name' => 'required|unique:products,product_name,' . $id . ',id',
            'product_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'category_id' => 'required|integer',
            'product_cost' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Error Validation', $validator->errors(), 400);
        }
        $product = Product::find($id);
        $product_name = $request->input('product_name');
        $slug = Str::slug($product_name);
        $category_id = $request->input('category_id');
        $product_description = $request->input('product_description');

        $product_image = $request->file('product_image');
        $product_cost = $request->input('product_cost');
        if ($product_image) {
            $uniquename = md5(time());
            $extension = $product_image->getClientOriginalExtension();
            $image_name = $uniquename . '.' . $extension;
            $product_image->move('site/uploads/product/', $image_name);
        }

        $product->product_name = $product_name;
        $product->slug = $slug;
        $product->category_id = $category_id;
        $product->product_description = $product_description;
        $product->product_cost = $product_cost;
        if ($product_image) {
            $product->product_image = $image_name;
        }
        $product->save();
        return $this->sendResponse(new ProductResource($product), ' product Edited');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $product = Product::find($id);
        if (is_null($product)) {
            return $this->sendError('product does not exist.');
        }
        if ($product->category_image) {
            unlink('site/uploads/product/' . $product->category_image);
        }
        $product->delete();
        return $this->sendResponse('Hello', 'product deleted.');
    }
}
