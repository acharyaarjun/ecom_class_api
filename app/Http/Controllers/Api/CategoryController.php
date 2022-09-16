<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
// category resources ko use garera data pthauna ko lagi suruma yo garna parxa! Category resources ko name lai CategoryResource ma badeko
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Category as CategoryResource;
use App\Http\Controllers\Api\BaseController as BaseController;
use App\Http\Resources\CategoryProduct as CategoryProductResource;


class CategoryController extends BaseController
{
    public function getCategories(Request $request)
    {
        $pageSize = $request->page_size ?? 10;
        // return response()->json($pageSize, 200);
        $categories = Category::orderby('id', 'desc')->paginate($pageSize);
        return CategoryResource::collection($categories);
    }
    public function getCategory($id)
    {
        $category = Category::find($id);

        if (is_null($category)) {
            return $this->sendError('Category Not found!');
        }
        return $this->sendResponse(new CategoryResource($category), 'Category with this id found');
    }
    public function deleteCategory($id)
    {
        $category = Category::find($id);

        if (is_null($category)) {
            return $this->sendError('Category Not found!');
        }

        $category->delete();

        return $this->sendResponse('', 'Category Deleted Successfully!');
    }
    public function postAddCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required|unique:categories,category_name',
            'category_image' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if($validator->fails()){
            return $this->sendError('Error Validation', $validator->errors(), 400);
        }


        $category_name = $request->input('category_name');
        // slug banaunako lagi
        $slug = Str::slug($category_name);
        $category_description = $request->input('category_description');

        $category_image = $request->file('category_image');
        if ($category_image) {
            $uniqename = md5(time());
            $extension = $category_image->getClientOriginalExtension();
            $image_name = $uniqename . '.' . $extension;
            $category_image->move('site/uploads/category/', $image_name);
        }

        $category = new Category;
        $category->category_name = $category_name;
        $category->slug = $slug;
        $category->category_description = $category_description;
        if ($category_image) {
            $category->category_image = $image_name;
        }

        $category->save();
        return $this->sendResponse(new CategoryResource($category), 'Category Added Successfully!');
    }
    public function postEditCategory(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            // 'category_name' => 'required|unique:categories,category_name,',
            'category_name' => 'required|unique:categories,category_name,'.$id.',id',
            'category_image' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);
        
        if($validator->fails()){
            return $this->sendError('Error Validation', $validator->errors(), 400);
        }

        $category = Category::find($id);
        if (is_null($category)) {
            return $this->sendError('Category Not found!');
        }
        $category_name = $request->input('category_name');
        // slug banaunako lagi
        $slug = Str::slug($category_name);
        $category_description = $request->input('category_description');

        $category_image = $request->file('category_image');
        if ($category_image) {
            $uniqename = md5(time());
            $extension = $category_image->getClientOriginalExtension();
            $image_name = $uniqename . '.' . $extension;
            $category_image->move('site/uploads/category/', $image_name);
            if ($category->category_image) {
                unlink('site/uploads/category/'.$category->category_image);
            }
        }

        $category->category_name = $category_name;
        $category->slug = $slug;
        $category->category_description = $category_description;
        if ($category_image) {
            $category->category_image = $image_name;
        }

        $category->save();
        return $this->sendResponse(new CategoryResource($category), 'Category Edited Successfully!');
    }
    public function getProductsWithCategory($id){
        $category = Category::find($id);

        if (is_null($category)) {
            return $this->sendError('Category Not found!');
        }
        return $this->sendResponse(new CategoryProductResource($category), 'Product with '.$category->category_name.' founds ');
    }
}
