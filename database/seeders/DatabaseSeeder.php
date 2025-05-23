<?php

namespace Database\Seeders;

use App\Models\Cottage;
use App\Models\Discount;
use App\Models\Opinion;
use App\Models\Package;
use App\Models\Promotion;
use App\Models\Reservation;
use App\Models\Rol;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Rol::factory()->create([
            'name_rol' => 'Admin',
        ]);
        Rol::factory()->create([
            'name_rol' => 'Usuario',
        ]);
        Cottage::factory()->create([
            'name_cottage' => 'Cabaña Coaba',
            'description' => 'La cabaña Coaba esta ubicado en el corazón de Arequipa, nuestra casa de campo ofrece mucho más que comodidad: es un espacio donde se siente la tranquilidad, la calma y la alegría de estar vivo. Con piscina privada, cuatrimotor para aventuras inolvidables y todos los servicios necesarios para una estadía perfecta, aquí cada momento invita a reconectar contigo mismo y con quienes amas. Más que un destino, es una experiencia de paz y bienestar que se guarda en el alma.',
            'capacity' => 12,
            'availability' => true,
            'rooms' => 12,
            'beds' => 12,
            'bathrooms' => 12,
        ]);
        Cottage::factory()->create([
            'name_cottage' => 'Cabaña Perkinson',
            'description' => 'La cabaña Coaba esta ubicado en el corazón de Arequipa, nuestra casa de campo ofrece mucho más que comodidad: es un espacio donde se siente la tranquilidad, la calma y la alegría de estar vivo. Con piscina privada, cuatrimotor para aventuras inolvidables y todos los servicios necesarios para una estadía perfecta, aquí cada momento invita a reconectar contigo mismo y con quienes amas. Más que un destino, es una experiencia de paz y bienestar que se guarda en el alma.',
            'capacity' => 12,
            'availability' => true,
            'rooms' => 12,
            'beds' => 12,
            'bathrooms' => 12,
        ]);
        User::factory()->create([
            'name' => 'Luis Martin',
            'email' => 'luis@gmail.com',
            'lastname' => 'Vilca Hilasaca',
            'phone' => '993763334',
            // 'country' => 'Perú',
            // 'user' => 'luis',
            'password' => bcrypt('12345678'),
            'rol_id' => '1',
        ]);
        Promotion::factory(5)->create();
        Package::factory(4)->create();
        $reservations = [
            [
                'user_id' => 1,
                'package_id' => 2,
                'date_start' => '2025-05-29 00:00:00',
                'date_end' => '2025-05-31 00:00:00',
                'total_price' => 2000,
                'state' => 1,
                'date_reservation' => '2025-05-29 00:00:00',
                'promotion_id' => null,
            ],
            [
                'user_id' => 1,
                'package_id' => 3,
                'date_start' => '2025-05-24 00:00:00',
                'date_end' => '2025-05-24 00:00:00',
                'total_price' => 600,
                'state' => 1,
                'date_reservation' => '2025-05-29 00:00:00',
                'promotion_id' => null,
            ],
            [
                'user_id' => 1,
                'package_id' => 1,
                'date_start' => '2025-05-16 00:00:00',
                'date_end' => '2025-05-17 00:00:00',
                'total_price' => 400,
                'state' => 1,
                'date_reservation' => '2025-05-15 00:00:00',
                'promotion_id' => null,
            ],
        ];

        foreach ($reservations as $data) {
            $reservation = Reservation::create($data);

            // Simula la asignación aleatoria de cabañas para cada reserva
            $reservation->cottages()->attach([rand(1, 2)]); // por ejemplo: asocia 1 cabaña aleatoria
        }
        // Opinion::factory(4)->create();
    }
}
