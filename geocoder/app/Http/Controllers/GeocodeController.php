<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Library\Services\GeoCode;

class GeocodeController extends Controller
{
    public function index(Request $request, GeoCode $geoCode){

    $address = $request->input('geodata');
    $lang = $request->input('leng');

    $data = $geoCode->geoData($address, $lang);

    return response()->json([
        'error' => null,
        'data' => $data
    ]);
 
    }
}
