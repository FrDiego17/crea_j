<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusStop extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'name',
        'latitude',
        'longitude',
        'address',
        'order'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'order' => 'integer',
    ];

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    // Obtener coordenadas como array
    public function getCoordinatesAttribute()
    {
        return [
            'lat' => (float) $this->latitude,
            'lng' => (float) $this->longitude
        ];
    }

    // Formatear para Google Maps
    public function toGoogleMapsFormat()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'position' => $this->coordinates,
            'address' => $this->address,
            'order' => $this->order
        ];
    }
}
