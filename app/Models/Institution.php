<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institution extends Model
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
    'owner',
    'legalRep',
    'email',
    'address',
    'logo',
    'logo_public_id',
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
