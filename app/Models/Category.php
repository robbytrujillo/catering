<?php

namespace App\Models;

use App\Models\CateringPackage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    //
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'photo',
    ];

    public function setNameAttribute($value) {
        $this->attibutes['name'] = $value;
        $this->attibutes['slug'] = Str::slug($value);
    }

    // 1 category memiliki > dari 1 cateringPackages
    public function cateringPackages(): HasMany {
        return $this->hasMany(CateringPackage::class);
    }

}