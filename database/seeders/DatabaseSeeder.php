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
        User::factory(4)->create();
        Cottage::factory(2)->create();
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
        Discount::factory(10)->create();
        Promotion::factory(5)->create();
        Package::factory(4)->create();
        // $reservations = [
        //     [
        //         'user_id' => 1,
        //         'package_id' => 2,
        //         'date_start' => '2024-10-29 00:00:00',
        //         'date_end' => '2024-10-31 00:00:00',
        //         'total_price' => 2000,
        //         'state' => 1,
        //         'date_reservation' => '2024-10-29 00:00:00',
        //         'discount_id' => null,
        //         'promotion_id' => null,
        //     ],
        //     [
        //         'user_id' => 2,
        //         'package_id' => 3,
        //         'date_start' => '2024-11-01 00:00:00',
        //         'date_end' => '2024-11-02 00:00:00',
        //         'total_price' => 600,
        //         'state' => 1,
        //         'date_reservation' => '2024-10-29 00:00:00',
        //         'discount_id' => null,
        //         'promotion_id' => null,
        //     ],
        //     [
        //         'user_id' => 3,
        //         'package_id' => 1,
        //         'date_start' => '2024-11-16 00:00:00',
        //         'date_end' => '2024-11-16 00:00:00',
        //         'total_price' => 400,
        //         'state' => 1,
        //         'date_reservation' => '2024-10-29 00:00:00',
        //         'discount_id' => null,
        //         'promotion_id' => null,
        //     ],
        //     [
        //         'user_id' => 1,
        //         'package_id' => 4,
        //         'date_start' => '2024-10-29 00:00:00',
        //         'date_end' => '2024-10-30 00:00:00',
        //         'total_price' => 2000,
        //         'state' => 1,
        //         'date_reservation' => '2024-10-29 00:00:00',
        //         'discount_id' => null,
        //         'promotion_id' => null,
        //     ],
        //     [
        //         'user_id' => 2,
        //         'package_id' => 2,
        //         'date_start' => '2024-11-23 00:00:00',
        //         'date_end' => '2024-11-23 00:00:00',
        //         'total_price' => 1000,
        //         'state' => 1,
        //         'date_reservation' => '2024-10-29 00:00:00',
        //         'discount_id' => null,
        //         'promotion_id' => null,
        //     ],
        //     [
        //         'user_id' => 3,
        //         'package_id' => 3,
        //         'date_start' => '2024-11-23 00:00:00',
        //         'date_end' => '2024-11-24 00:00:00',
        //         'total_price' => 800,
        //         'state' => 1,
        //         'date_reservation' => '2024-10-29 00:00:00',
        //         'discount_id' => null,
        //         'promotion_id' => null,
        //     ],
        //     [
        //         'user_id' => 4,
        //         'package_id' => 4,
        //         'date_start' => '2024-11-30 00:00:00',
        //         'date_end' => '2024-11-30 00:00:00',
        //         'total_price' => 400,
        //         'state' => 1,
        //         'date_reservation' => '2024-10-29 00:00:00',
        //         'discount_id' => null,
        //         'promotion_id' => null,
        //     ],
        //     [
        //         'user_id' => 5,
        //         'package_id' => 2,
        //         'date_start' => '2024-12-07 00:00:00',
        //         'date_end' => '2024-12-08 00:00:00',
        //         'total_price' => 800,
        //         'state' => 1,
        //         'date_reservation' => '2024-10-29 00:00:00',
        //         'discount_id' => null,
        //         'promotion_id' => null,
        //     ],
        //     [
        //         'user_id' => 1,
        //         'package_id' => 2,
        //         'date_start' => '2024-12-13 00:00:00',
        //         'date_end' => '2024-12-13 00:00:00',
        //         'total_price' => 2000,
        //         'state' => 1,
        //         'date_reservation' => '2024-10-29 00:00:00',
        //         'discount_id' => null,
        //         'promotion_id' => null,
        //     ],
        //     [
        //         'user_id' => 2,
        //         'package_id' => 2,
        //         'date_start' => '2024-12-14 00:00:00',
        //         'date_end' => '2024-12-15 00:00:00',
        //         'total_price' => 1000,
        //         'state' => 1,
        //         'date_reservation' => '2024-10-29 00:00:00',
        //         'discount_id' => null,
        //         'promotion_id' => null,
        //     ],
        //     [
        //         'user_id' => 3,
        //         'package_id' => 2,
        //         'date_start' => '2024-12-14 00:00:00',
        //         'date_end' => '2024-12-15 00:00:00',
        //         'total_price' => 500,
        //         'state' => 1,
        //         'date_reservation' => '2024-10-29 00:00:00',
        //         'discount_id' => null,
        //         'promotion_id' => null,
        //     ],
        //     [
        //         'user_id' => 4,
        //         'package_id' => 2,
        //         'date_start' => '2024-12-16 00:00:00',
        //         'date_end' => '2024-12-18 00:00:00',
        //         'total_price' => 800,
        //         'state' => 1,
        //         'date_reservation' => '2024-10-29 00:00:00',
        //         'discount_id' => null,
        //         'promotion_id' => null,
        //     ],
        //     [
        //         'user_id' => 5,
        //         'package_id' => 2,
        //         'date_start' => '2024-12-16 00:00:00',
        //         'date_end' => '2024-12-18 00:00:00',
        //         'total_price' => 200,
        //         'state' => 1,
        //         'date_reservation' => '2024-10-29 00:00:00',
        //         'discount_id' => null,
        //         'promotion_id' => null,
        //     ],
        // ];

        // foreach ($reservations as $reservation) {
        //     Reservation::create($reservation);
        // }
        // Opinion::factory(4)->create();
    }
}
