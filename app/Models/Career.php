<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Career extends Model
{
    use HasFactory;
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'modality',
        'typeOfPeriod',
        'numOfPeriods'
    ];

    public function institution() {
        return $this->belongsTo(Institution::class);
    }

}
