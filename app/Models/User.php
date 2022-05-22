<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{    
    use HasApiTokens, HasFactory, Notifiable;
    public $timestamps = false;

    protected $table="users";

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'tipoUsuario',
        'contrasenia',
        'nombres',
        'activo',
        'apellidos',
        'correo',
        'telefono'
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

    public function scopeSearchDireccion($query)
    {
        return $query->where('tipoUsuario', 'direccion');
    }

    public function scopeSearchPlaneacion($query)
    {
        return $query->where('tipoUsuario', 'planeacion');
    }
}
