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
        'meta',
        'rvoe',
        'estado',
        'career_id',
        'formatoInstalaciones'
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
}
