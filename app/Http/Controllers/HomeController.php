<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller{
    
    public function inicio() {
        
        return view('home');
    }
    public function recibir(Request $request){
    
        return view('principal')
                ->with('ubicacion_actual',$request->ubicacion_actual)
                ->with('address_destiny',$request->address_destiny)
                ->with('code',$request->code);
    }
}