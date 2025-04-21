<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class InventarioController extends Controller
{

    public function registraInventario()
    {
        return view('inventario.reginventario');
    }

}
