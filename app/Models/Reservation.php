<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'discount_id',
        'promotion_id',
        'package_id',
        'date_start',
        'date_end',
        'total_price',
        'state',
        'date_reservation'

    ];
    public function setCreatedAtAttribute($value)
    {
        date_default_timezone_set("America/Lima");
        $this->attributes["created_at"] = Carbon::now();
    }

    public function setUpdatedAtAttribute($value)
    {
        date_default_timezone_set("America/Lima");
        $this->attributes["updated_at"] = Carbon::now();
    }

    public function user()
    {
        return $this->belongsTo(User::class);  // Una reserva pertenece a un usuario
    }
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    // Relación con la cabaña
    public function cottages()
    {
        return $this->belongsToMany(Cottage::class, 'cottage_reservation');
    }
    // Relación con la promoción
    public function promotion()
    {
        return $this->belongsTo(Promotion::class);  // Una reserva puede tener una promoción
    }
    // Relación uno a uno con el modelo de Comentario (por ejemplo, 'opinion')
    public function opinion()
    {
        return $this->hasOne(Opinion::class);
    }
}
