<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Category as CategoryResource;
use App\Models\Category;


class Product extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_name' => $this->product_name,
            'slug' => $this->slug,
            'product_image' => $this->product_image != null ? asset('site/uploads/product/' . $this->product_image) : null,
            'product_description' => $this->product_description,
            'product_cost' => $this->product_cost,
            'created_at' => $this->created_at->format('m/d/Y'),
            'updated_at' => $this->updated_at->format('m/d/Y'),
            'category_id' => new CategoryResource(Category::findOrFail($this->category_id)),
        ];
    }
}
