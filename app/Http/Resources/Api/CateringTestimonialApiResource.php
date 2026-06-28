<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\Api\CateringPackageApiResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CateringTestimonialApiResource extends JsonResource
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
            'photo' => $this->photo,
            'message' => $this->message,
            'cateringPackage' => new CateringPackageApiResource($this->whenLoaded('cateringPackage')),
        ];
    }
}