<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'max_person',
        'price_monday_to_thursday',
        'price_friday_to_sunday',
        'cottage_id'
    ];

    public function cottages()
    {
        return $this->belongsToMany(Cottage::class, 'cottage_packages');
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
