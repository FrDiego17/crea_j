<?php
// app/Models/Route.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_location',
        'end_location',
        'price',
        'color',
        'route_data',
        'route_polyline',
        'route_bounds',
        'is_active'
    ];

    protected $casts = [
        'route_data' => 'array',
        'route_bounds' => 'array',
        'is_active' => 'boolean',
    ];

    public function busStops(): HasMany
    {
        return $this->hasMany(BusStop::class)->orderBy('order');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(RouteSchedule::class);
    }

    public function activeSchedules(): HasMany
    {
        return $this->hasMany(RouteSchedule::class)->where('is_active', true);
    }

    // Obtener horarios agrupados por día
    public function getSchedulesByDay()
    {
        return $this->activeSchedules()
            ->get()
            ->groupBy('day_of_week')
            ->map(function ($schedules) {
                return $schedules->pluck('departure_time')->sort()->values();
            });
    }

    // Scope para rutas activas
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Obtener el próximo horario disponible
    public function getNextDeparture()
    {
        $currentDay = strtolower(now()->locale('es')->dayName);
        $currentTime = now()->format('H:i:s');
        
        $todaySchedules = $this->activeSchedules()
            ->where('day_of_week', $currentDay)
            ->where('departure_time', '>', $currentTime)
            ->orderBy('departure_time')
            ->first();

        if ($todaySchedules) {
            return [
                'day' => $currentDay,
                'time' => $todaySchedules->departure_time,
                'is_today' => true
            ];
        }

        // Si no hay más horarios hoy, buscar el próximo día
        $days = ['lunes', 'martes', 'miercoles', 'jueves', 'viernes', 'sabado', 'domingo'];
        $currentDayIndex = array_search($currentDay, $days);
        
        for ($i = 1; $i <= 7; $i++) {
            $nextDayIndex = ($currentDayIndex + $i) % 7;
            $nextDay = $days[$nextDayIndex];
            
            $nextSchedule = $this->activeSchedules()
                ->where('day_of_week', $nextDay)
                ->orderBy('departure_time')
                ->first();
                
            if ($nextSchedule) {
                return [
                    'day' => $nextDay,
                    'time' => $nextSchedule->departure_time,
                    'is_today' => false
                ];
            }
        }

        return null;
    }
}