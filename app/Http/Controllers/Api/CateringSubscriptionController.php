<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CateringPackage;
use Illuminate\Http\Request;

class CateringSubscriptionController extends Controller
{
    //
    public function store(StoreCateringSubscribeRequest $request) {
        $validatedData = $request->validated();

        $cateringPackage = CateringPackage::find($validatedData['catering_package_id']);

        if (!$cateringPackage) {
            return response()->json(['message' => 'Tier package not found, please choose the existings tiers available']);
        }
    }
}