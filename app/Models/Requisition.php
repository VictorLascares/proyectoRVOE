<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'procedure',
        'requestNumber',
        'rvoe',
        'facilitiesFormat',
        'format_public_id',
        'status',
        'evaNum',
        'cata',
        'ota',
        'dueDate',
        'revokedDate',
        'requisitionDate',
        'latencyDate',
        'fecha_m',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
    ];

    public function scopeRvoe($query,$rvoe){
        return $query->where('rvoe',$rvoe);
    }

    public function scopeSearchcareerid($query,$career_id){
        return $query->where('career_id',$career_id);
    }
    public function scopeSearchcareeridMax($query,$career_id){
        return $query->where('career_id',$career_id);
    }
    public function scopeCheckpendiente($query){
        return $query->where('estado','pendiente')
                     ->max('created_at');
    }
    public function scopeSearchDate($query,$year){
        return $query->whereYear('created_at',$year);
    }

    public function scopeNoSolicitud($query){
        return $query->whereNotNull('numero_solicitud');
    }
}
