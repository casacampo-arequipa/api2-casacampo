<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cottage extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_cottage',
        'description',
        'price',
        'capacity',
        'availability',
        'rooms',
        'beds',
        'bathrooms'
    ];

    public function reservations()
    {
        return $this->belongsToMany(Package::class, 'cottage_reservation');  // Una cabaña puede estar asociada a muchas reservas
    }
}
