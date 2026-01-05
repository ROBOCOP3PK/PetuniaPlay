<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'animal_section_id',
        'name',
        'birth_date',
        'weight',
        'photo',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'weight' => 'decimal:2',
    ];

    protected $appends = ['display_photo', 'age'];

    // Relationships

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function animalSection()
    {
        return $this->belongsTo(AnimalSection::class);
    }

    // Accessors

    /**
     * Obtiene la foto a mostrar (la del pet o el icono de la categoría)
     */
    public function getDisplayPhotoAttribute()
    {
        if ($this->photo) {
            return $this->photo;
        }

        // Si no tiene foto, usar el icono de la categoría
        if ($this->animalSection) {
            return $this->animalSection->icon;
        }

        // Por defecto, un emoji de perro
        return '🐕';
    }

    /**
     * Calcula la edad de la mascota
     */
    public function getAgeAttribute()
    {
        if (!$this->birth_date) {
            return null;
        }

        $birthDate = $this->birth_date;
        $now = now();

        $years = $birthDate->diffInYears($now);
        $months = $birthDate->copy()->addYears($years)->diffInMonths($now);

        if ($years > 0) {
            return $years . ' año' . ($years > 1 ? 's' : '') . ($months > 0 ? ' y ' . $months . ' mes' . ($months > 1 ? 'es' : '') : '');
        }

        if ($months > 0) {
            return $months . ' mes' . ($months > 1 ? 'es' : '');
        }

        $days = $birthDate->diffInDays($now);
        return $days . ' día' . ($days > 1 ? 's' : '');
    }
}
