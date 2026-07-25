<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCateringSubscribeRequest;
use App\Models\CateringPackage;
use App\Models\CateringSubscription;
use App\Models\CateringTier;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

        // Calculate ended_at based on started_at and duration
        $startedAt = Carbon::parse($validatedData['started_at']);
        $endedAt = $startedAt->copy()->addDays($cateringTier->duration);

        $price = $cateringTier->price;
        $tax = 0.11;
        $totalTax = $tax * $price;
        $grandTotal = $price + $tax;

        $validatedData['price'] = $price;
        $validatedData['total_tax_amount'] = $totalTax;
        $validatedData['total_amount'] = $grandTotal;
        
        $validatedData['quantity'] = $cateringTier->quantity;
        $validatedData['duration'] = $cateringTier->duration;
        $validatedData['city'] = $cateringPackage->city;
        $validatedData['delivery_time'] = "Lunch Time";

        // Add started_at and ended_at to validated data
        $validatedData['started_at'] = $startedAt->format('Y-d-m');
        $validatedData['ended_at'] = $endedAt->format('Y-d-m');
        
        $validatedData['is_paid'] = 'false';

        $validatedData['booking_trx_id'] = CateringSubscription::generateUniqueTrxId();

        $bookingTransaction = CateringSubscription::create($validatedData);

        $bookingTransaction->load(['cateringPackage', 'cateringTier']);

        return new ApiCateringSubscription($bookingTransaction);
    }
}