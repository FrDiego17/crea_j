<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Conductore
 *
 * @property $id
 * @property $rutas_id
 * @property $nombre
 * @property $apellido
 * @property $email
 * @property $dui
 * @property $telefono
 * @property $licencia
 * @property $TipoVehiculo
 * @property $created_at
 * @property $updated_at
 *
 * @property Ruta $ruta
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Conductore extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['rutas_id', 'nombre', 'apellido', 'email', 'dui', 'telefono', 'licencia', 'TipoVehiculo'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ruta()
    {
        return $this->belongsTo(Ruta::class, 'rutas_id');
    }
    
}
