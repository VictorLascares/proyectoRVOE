<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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


  public function scopeSearchMunicipality($query, $Municipality_id)
  {
    return $query->where('id', $Municipality_id);
  }

  public function institutions()
  {
    return $this->hasMany(Institution::class, 'municipalitie_id', 'id');
  }
}
