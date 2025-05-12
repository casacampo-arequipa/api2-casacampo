<?php

namespace App\Http\Controllers\Tienda;

use App\Http\Controllers\Controller;
use App\Http\Resources\Tienda\HomeCollection;
use App\Models\Cottage;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        $cottages = Cottage::all();
        return response()->json([
            'cottages' => new HomeCollection($cottages),
        ]);
    }
}
