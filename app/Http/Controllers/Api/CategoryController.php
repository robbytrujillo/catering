<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\CategoryApiResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //
    public function index() {
        $categories = Category::with(['cateringPackages'])->get();
        return CategoryApiResource::collection($categories);
    }

    public function show(Category $category) { // jakarta-selatan, surabaya
        $category->load(['cateringPackages', 'cateringPackages.city', 'cateringPackages.tiers']);

        $category->loadCount('cateringPackages');

        return new CategoryApiResource($category);
    }
}