<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'plan',
        'status',
    ];

    public function scopeSearchrequisitionid($query,$requisition_id){
        return $query->where('requisition_id',$requisition_id);
    }

    public function scopeSearchPlan($query,$plan){
        return $query->where('plan',$plan);
    }
}
