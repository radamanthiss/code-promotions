<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
class ValidateController extends Controller{
    
    public function envio(Request $request) {
        
        $origin=$request->origin;
        $destiny=$request->destiny;
        $code=$request->code;
        print_r($origin);
        print_r($destiny);
        
    }
}