<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Format extends Model
{
    use HasFactory;
    public $timestamps = false;


    protected $fillable = [
        'formato',
        'valido',
        'justificacion',
    ];

    public function scopeSearchrequisitionid($query,$requisition_id){
        return $query->where('requisition_id',$requisition_id);
    }

    public function scopeSearchformat($query,$formato){
        return $query->where('format',$formato);
    }

}
