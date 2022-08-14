<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reason extends Model
{
    protected $fillable = [
      'name',
      'active',
      'leads_followup_id'
    ];

    //relations

    public function leadsFollowup(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(LeadsFollowup::class,'leads_followup_id');
    }

    public function leadActivities(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(LeadActivity::class);
    }

}
