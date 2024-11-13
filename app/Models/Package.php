<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'name',
        'description',
        'max_person',
        'price_monday_to_thursday',
        'price_friday_to_sunday',
        'cottage_id',
        'cleaning',
        'guarantee',
        'img'
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
