<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Ruta
 *
 * @property $id
 * @property $origen
 * @property $created_at
 * @property $updated_at
 *
 * @property Conductore[] $conductores
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Ruta extends Model
{
    
    protected $perPage = 20;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['origen'];


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function conductores()
    {
        return $this->hasMany(Conductore::class, 'rutas_id');
    }
    
}
