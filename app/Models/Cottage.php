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
        'capacity',
        'availability',
        'rooms',
        'beds',
        'bathrooms',
        'main_image',
        'gallery_images',
    ];
    protected $casts = [
        'gallery_images' => 'array', // ✅ para usarlo como array en controladores/resources
    ];
    public function packages()
    {
        return $this->belongsToMany(Package::class, 'cottage_packages');
    }
    public function reservations()
    {
        return $this->belongsToMany(Package::class, 'cottage_reservation');  // Una cabaña puede estar asociada a muchas reservas
    }
}
