<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\Api\CateringPackageApiResource;
use App\Http\Resources\Api\TierBenefitApiResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CateringTierApiResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'duration' => $this->duration,
            'photo' => $this->photo,
            'cateringPackage' => new CateringPackageApiResource($this->whenLoaded('cateringPackage')),
            'benefits' => new TierBenefitApiResource($this->whenLoaded('benefits')),
        ];
    }
}