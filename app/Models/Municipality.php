<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
  use HasFactory;
  public $timestamps = false;

  /**
   * The attributes that are mass assignable.
   *
   * @var string[]
   */
  protected $fillable = [
    'nombre',
    'clave'
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array
   */
  protected $hidden = [];

  /**
   * The attributes that should be cast.
   *
   * @var array
   */
  protected $casts = [];
}
