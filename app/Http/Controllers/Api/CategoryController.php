<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController as BaseController;
// category resources ko use garera data pthauna ko lagi suruma yo garna parxa! Category resources ko name lai CategoryResource ma badeko
use App\Http\Resources\Category as CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;


class CategoryController extends BaseController
{
    public function getCategories(){
        $categories = Category::all();
        return $this->sendResponse(CategoryResource::collection($categories), 'Category Fetched!');
    }
    
    public function getCategory($id){
        $category = Category::find($id);

        if(is_null($category)){
            return $this->sendError('Category Not found!');
        }
        return $this->sendResponse(new CategoryResource($category), 'Category with this id found');
    }
    public function deleteCategory($id){
        $category = Category::find($id);

        if(is_null($category)){
            return $this->sendError('Category Not found!');
        }
        
        $category->delete();

        return $this->sendResponse('', 'Category Deleted Successfully!');

    }
}
