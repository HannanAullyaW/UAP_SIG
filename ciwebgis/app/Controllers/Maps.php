<?php

namespace App\Controllers;

class Maps extends BaseController
{
    public function index()
    {

        $fileName = base_url("maps/bandarlampung.geojson"); //add
        $file = file_get_contents($fileName); //add
        $file = json_decode($file); //add

        $features = $file->features; //add

       
        return view('maps/index', [
            'data' => $features,

        ]);
    }
}
