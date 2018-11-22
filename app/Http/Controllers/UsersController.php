<?php
namespace App\Http\Controllers;

use App\User;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Jcf\Geocode\Geocode;

class UsersController extends Controller{
    
    
    public function createNewUser(Request $request) {
        $str_logTxt = __CLASS__ . "->" . __FUNCTION__ . "::";
        $str_logTxt .= "RESQUEST: " . json_encode($request->toArray()) . ";";
        try {
            //Busca si existe el usuario que se trata de crear
            $arrobj_Code = User::where('code_id', $request->input('code_id'))
            ->where('name', $request->input('name'))
            ->get();
            
            $response = Geocode::make()->address($request->ubicacion_actual);
            $latitud=$response->latitude();
            $longitud=$response->longitude();
            
            if (!$arrobj_Code->isEmpty()) {
                $str_logTxt .= "RESPONSE_createUsers: Usuario ya existente" . json_encode($arrobj_Code) . ";";
                return response()->json(['error' => 'El usuario que trata de crear ya existe.'], Response::HTTP_CONFLICT);
            }
            else {
                
                $obj_Code = DB::table('users')->insert([
                    'code_id' => $request->code_id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'state' => $request->state,
                    'payment_method' => $request->payment_method,
                    'ubicacion_actual' => $request->ubicacion_actual,
                    'lat' => $latitud,
                    'lng' => $longitud,
                    'created_at' => date("Y-m-d h:m:s"),
                    'updated_at' => date("Y-m-d h:m:s")
                ]);
            }
            
        } catch (\Exception $e) {
            $str_logTxt .= "[ERROR= " . $e->getMessage() . "];";
            Log::debug($str_logTxt);
            return response()->json(['error' => 'Error interno en servidor.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        $str_logTxt .= "RESPONSE_createNewUser: " . json_encode($obj_Code) . ";";
        Log::debug($str_logTxt);
        
        return response()->json($obj_Code);
        
    }
    
    public function  getOrigin($id) {
        $str_logTxt = __CLASS__ . "->" . __FUNCTION__ . "::";
        $str_logTxt .= "RESQUEST: user_id:$id;";
        
        
        $obj_origin=User::getOrigin($id);
        $str_logTxt .="RESPONSE result_users:" . json_encode($obj_origin) . ";";
        //Identifica si el usuario existe y pertenece a la aplicacion
        if (empty($obj_origin)) {
            $str_logTxt .= "RESPONSE: La direccion que intenta consultar no existe";
            return response()->json(['error' => 'La direccion que intenta consultar no existe'], Response::HTTP_FORBIDDEN);
        }
        
        $str_logTxt .= "RESPONSE: " . json_encode($obj_origin) . ";";
        Log::debug($str_logTxt);
        return response()->json($obj_origin);
        
    }
    
    public function updateOrigin(Request $request) {
        $str_logTxt = __CLASS__ . "->" . __FUNCTION__ . "::";
        $str_logTxt .= "RESQUEST: " . json_encode($request->toArray()) . ";";
        
        $response = Geocode::make()->address($request->ubicacion_actual);
        $new_origin=$request->ubicacion_actual;
        $latitud=$response->latitude();
        $longitud=$response->longitude();
        
        
        try {
            //Valida si tiene parametros para actualizar
            if (!($request->exists('ubicacion_actual'))) {
                $str_logTxt .= "RESPONSE: No se encontro ninguno de los parametros editables;";
                Log::debug($str_logTxt);
                
                return response()->json(['error' => 'No se encontro ninguno de los parametros editables'], Response::HTTP_BAD_REQUEST);
            }
            //Valida si existe usuario
            $obj_user = User::find($request->id);
            if (is_null($obj_user)) {
                $str_logTxt .= "RESPONSE: El usuario que intenta modificar no existe;";
                Log::debug($str_logTxt);
                
                return response()->json(['error' => 'El usuario que intenta modificar no existe'], Response::HTTP_NOT_FOUND);
            }
            
            //actualiza parametros en la base de datos
            
            DB::table('users')
            ->where('id', $request->id)
            ->update(['ubicacion_actual' => $new_origin,
                'lat' => $latitud,
                'lng' => $longitud
            ]);
            
            $str_logTxt .= "RESPONSE: " . json_encode($obj_user) . ";";
            Log::debug($str_logTxt);
        } catch (\Exception $e) {
            $str_logTxt .= "[ERROR= " . $e->getMessage() . "];";
            Log::debug($str_logTxt);
            $res=array("id"=> 0,
                "code_id"=> 0,
                "name"=> "",
                "email"=> "error@gmail.com",
                "state"=> "",
                "payment_method"=> "",
                "ubicacion_actual"=> "",
                "lat"=> "0000",
                "lng"=> "0000");
            return response()->json($res);
        }
        Log::debug($str_logTxt);
        return response()->json($obj_user);
    }
}