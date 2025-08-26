<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RouteSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'route_id',
        'day_of_week',
        'departure_time',
        'is_active'
    ];

    protected $casts = [
        'departure_time' => 'datetime:H:i',
        'is_active' => 'boolean',
    ];

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    // Scope para horarios activos
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope por día de la semana
    public function scopeForDay($query, $day)
    {
        return $query->where('day_of_week', $day);
    }

    // Obtener el nombre del día en español
    public function getDayNameAttribute()
    {
        $days = [
            'lunes' => 'Lunes',
            'martes' => 'Martes',
            'miercoles' => 'Miércoles',
            'jueves' => 'Jueves',
            'viernes' => 'Viernes',
            'sabado' => 'Sábado',
            'domingo' => 'Domingo'
        ];

        return $days[$this->day_of_week] ?? $this->day_of_week;
    }

    // Formatear hora para mostrar
    public function getFormattedTimeAttribute()
    {
        return $this->departure_time->format('H:i');
    }
}