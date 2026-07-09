<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CateringPackageController extends Controller
{
    //
    public function index() {
        $packages = CateringPackage::with(['cateringTestimonial'])->get();
        return CateringPackageApiResource::collection($packages);
    }
}