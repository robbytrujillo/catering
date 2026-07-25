<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CateringPackage;
use App\Models\CateringTier;
use Illuminate\Http\Request;

class CateringSubscriptionController extends Controller
{
    //
    public function store(StoreCateringSubscribeRequest $request) {
        $validatedData = $request->validated();

        $cateringPackage = CateringPackage::find($validatedData['catering_package_id']);

        if (!$cateringPackage) {
            return response()->json(['message' => 'Package not found'], 404);
        }

        $cateringTier = CateringTier::find($validatedData('catering_tier_id'));
        
        if ($cateringTier) {
            return response()->json(['message' => 'Tier package not found, please choose the existings tiers available'], 404);
        }

        // Handle file upload
        if ($request->hasFile('proof')) {
            $filePath = $request->file('proof')->store('payment/proofs', 'public');
            $validatedData['proof'] = $filePath;
        }
    }
}