<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LeadDiploma extends Model
{
    protected $fillable = [
        'diploma_id',
        'lead_id',
        'category_id',
        'vendor_id',
    ];

    //append
    protected $appends = [
        'diploma_name',
        'vendor_name',
        'category_name',
    ];

    public function getDiplomaNameAttribute()
    {
        return $this->diploma()->get('name')->pluck('name')->first();
    }

    public function getVendorNameAttribute()
    {
        return $this->vendor()->get('name')->pluck('name')->first();
    }
    public function getCategoryNameAttribute()
    {
        return $this->category()->get('name')->pluck('name')->first();
    }

    //relations

    public function diploma(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Diploma::class,'diploma_id');
    }

    public function lead(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Lead::class,'lead_id');
    }

    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class,'category_id');
    }

    public function vendor(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Vendor::class,'vendor_id');
    }
}
