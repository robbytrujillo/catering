<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\Api\CityApiResource;
use App\Models\Category;
use App\Models\CateringBonus;
use App\Models\CateringPhoto;
use App\Models\CateringTestimonial;
use App\Models\CateringTier;
use App\Models\City;
use App\Models\Kitchen;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CateringPackageApiResource extends JsonResource
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
            'slug' => $this->slug,
            'is_popular' => $this->is_popular,
            'thumbnail' => $this->thumbnail,
            'about' => $this->about,
            'city' => new CityApiResource($this->whenLoaded('city')),
            'category' => new Category($this->whenLoaded('category')),
            'kitchen' => new Kitchen($this->whenLoaded('kitchen')),
            'photos' => CateringPhoto::collection($this->whenLoaded('photos')),
            'bonuses' => CateringBonus::collection($this->whenLoaded('bonuses')),
            'testimonials' => CateringTestimonial::collection($this->whenLoaded('testimonials')),
            'tiers' => CateringTier::collection($this->whenLoaded('tiers')),

        ];
    }
}