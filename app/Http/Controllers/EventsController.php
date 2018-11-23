<?php
namespace App\Http\Controllers;

use App\Event;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Travel;
use Jcf\Geocode\Geocode;


class EventsController extends Controller{
    
    
    public function updateRadius(Request $request) {
        $str_logTxt = __CLASS__ . "->" . __FUNCTION__ . "::";
        $str_logTxt .= "RESQUEST: " . json_encode($request->toArray()) . ";";
        
        
        try {
            //Valida si tiene parametros para actualizar
            if (!($request->exists('radio'))) {
                $str_logTxt .= "RESPONSE: No se encontro ninguno de los parametros editables;";
                Log::debug($str_logTxt);
                
                return response()->json(['error' => 'No se encontro ninguno de los parametros editables'], Response::HTTP_BAD_REQUEST);
            }
            //Valida si existe el evento
            $obj_user = Event::find($request->id);
            if (is_null($obj_user)) {
                $str_logTxt .= "RESPONSE: El evento que intenta modificar no existe;";
                Log::debug($str_logTxt);
                
                return response()->json(['error' => 'El evento que intenta modificar no existe'], Response::HTTP_NOT_FOUND);
            }
            
            //actualiza parametros en la base de datos
            
            DB::table('events')
            ->where('id', $request->id)
            ->update(['radio' => $request->radio]);
            
            $str_logTxt .= "RESPONSE: " . json_encode($obj_user) . ";";
            Log::debug($str_logTxt);
        } catch (\Exception $e) {
            $str_logTxt .= "[ERROR= " . $e->getMessage() . "];";
            Log::debug($str_logTxt);
            $res=array("id"=> 0,
                "code_id"=> 0,
                "name"=> "",
                "address"=> "",
                "city"=> "",
                "place"=> "",
                "lat"=> "0000",
                "lng"=> "0000",
                "radio"=> "0");
            return response()->json($res);
        }
        Log::debug($str_logTxt);
        return response()->json($obj_user);
    }
    
    public function newEvent(Request $request) {
        $str_logTxt = __CLASS__ . "->" . __FUNCTION__ . "::";
        $str_logTxt .= "RESQUEST: " . json_encode($request->toArray()) . ";";
        try {
            //Busca si existe el codigo que se trata de crear
            $arrobj_Code = Event::where('code_id', $request->input('code_id'))
            ->where('name', $request->input('name'))
            ->get();
            
            $response = Geocode::make()->address($request->address);
            $latitud=$response->latitude();
            $longitud=$response->longitude();
            
            if (!$arrobj_Code->isEmpty()) {
                $str_logTxt .= "RESPONSE_createEvent: Evento ya existe" . json_encode($arrobj_Code) . ";";
                return response()->json(['error' => 'El evento que trata de generar ya existe.'], Response::HTTP_CONFLICT);
            }
            else {
                
                $obj_Code = DB::table('events')->insert([
                    'code_id' => $request->code_id,
                    'name' => $request->name,
                    'address' => $request->address,
                    'city' => $request->city,
                    'place' => $request->place,
                    'lat' => $latitud,
                    'lng' => $longitud,
                    'radio' => $request->radio,
                    'created_at' => date("Y-m-d h:m:s"),
                    'updated_at' => date("Y-m-d h:m:s")
                ]);
            }
            
        } catch (\Exception $e) {
            $str_logTxt .= "[ERROR= " . $e->getMessage() . "];";
            Log::debug($str_logTxt);
            return response()->json(['error' => 'Error interno en servidor.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        $str_logTxt .= "RESPONSE_createNewEvent: " . json_encode($obj_Code) . ";";
        Log::debug($str_logTxt);
        
        return response()->json($obj_Code);
    }
    
    
    public function validateCodeEvent(Request $request){
        $str_logTxt = __CLASS__ . "->" . __FUNCTION__ . "::";
        $str_logTxt .= "RESQUEST: " . json_encode($request->toArray()) . ";";
        //obtenemos la ubicacion del evento        
        $obj_eventPlace=Event::getPlaceEvent($request->code_id);
        
        $str_logTxt .= "Place_Event :". json_encode($obj_eventPlace). ";";
        //obtenemos el origen del usuario
        $obj_user=User::getUbicationUser($request->code_id);
        
        //obtenemos el destino del usuario
        $obj_destinyUser=Travel::getDestiny($obj_user->id);
        
        //direccion_evento
        $address_event=$obj_eventPlace->address;
        $latitud_evento=$obj_eventPlace->lat;
        $longitud_evento=$obj_eventPlace->lng;
        $radio=$obj_eventPlace->radio;
        
        //origen_usuario
        $direccion_origen=$obj_user->ubicacion_actual;
        $latitud_origen=$obj_user->lat;
        $longitud_origen=$obj_user->lng;
        
        //destino_usuario
        $destino=$obj_destinyUser->address_destiny;
        $latitud_destino=$obj_destinyUser->lat;
        $longitud_destino=$obj_destinyUser->lng;
        
        $obj_validate_originDistance = Event::getDistanceOrigin($latitud_evento, $longitud_evento, $radio,$request->code_id);
        $obj_validate_destinyDistance = Travel::getDistanceDestiny($latitud_evento, $longitud_evento, $radio,$obj_user->id);
        
        
        if(empty($obj_validate_originDistance) && empty($obj_validate_destinyDistance)){
            $arr_response = ['message' => 'La direccion de origen o destino no se encuentra dentro del rango del evento, imposible redimir codigo'];
        }
        else  {
            $arr_response = ['message' => 'Direccion origen o destino aceptada, codigo valido para realizar el viaje'];
        }
        Log::debug($str_logTxt);
        return response()->json($arr_response);
       
    }
    
    public static function validateCode($code,$ubicacion_actual,$address_destiny) {
        
        $str_logTxt = __CLASS__ . "->" . __FUNCTION__ . "::";
        
        if(empty($obj_eventPlace=Event::getPlacebyCode($code))){
                
            $str_logTxt .= "RESPONSE: El codigo que acaba de ingresar no es valido;\n";
            Log::debug($str_logTxt);
            
            
            $arr_response= ['message' => 'El codigo que acaba de ingresar no es valido'];
        }
        else{
    
        $str_logTxt .= "Place_Event :". json_encode($obj_eventPlace). ";";
        //obtenemos el origen del usuario
        //Valida si existe codigo
        $response = Geocode::make()->address($ubicacion_actual);
        $new_origin=$ubicacion_actual;
        $latitud=$response->latitude();
        $longitud=$response->longitude();
        
        //$obj_users = User::find($obj_eventPlace->id);
        $obj_users = User::getUbicationUser($obj_eventPlace->id);
        if (is_null($obj_users)) {
            $str_logTxt .= "RESPONSE: El codigo no se encuentra asociado a un usuario o no existe;\n";
            Log::debug($str_logTxt);
            
            return response()->json(['error' => 'El codigo no se encuentra asociado a un usuario o no existe'], Response::HTTP_NOT_FOUND);
        }
        
        //actualiza parametros en la base de datos Cra 56a #61-25  Cra 56a #61-25
        
        DB::table('users')
        ->where('code_id', $obj_eventPlace->id)
        ->update(['ubicacion_actual' => $new_origin,
            'lat' => $latitud,
            'lng' => $longitud
        ]);
        
        $str_logTxt .= "RESPONSE_getUbicationUser1: " . json_encode($obj_users) . ";\n";
        Log::debug($str_logTxt);
        
        $obj_user=User::getUbicationUser($obj_eventPlace->id);
        
        
        if(is_null($obj_user)){
            $str_logTxt .= "RESPONSE: El valor que intenta modificar no existe;";
            Log::debug($str_logTxt);
            
            return response()->json(['error' => 'El valor que intenta modificar no existe'], Response::HTTP_NOT_FOUND);
            
        }
        
        $destiny=Geocode::make()->address($address_destiny);
        $new_destiny=$address_destiny;
        $latitud_destiny=$destiny->latitude();
        $longitud_destiny=$destiny->longitude();
        //obtenemos el destino del usuario
        $obj_destinyUser=Travel::getDestiny($obj_users->id);
        
        $str_logTxt .="RESPONSE_destinyUser1: " .json_encode($obj_destinyUser) .";\n";
        Log::debug($str_logTxt);
        
        $total_value = mt_rand(10000,100000);
        
        if (empty($obj_destinyUser)){
            $obj_Code = DB::table('travels')->insert([
                'user_id' => $obj_user->id,
                'total_value' => $total_value,
                'address_destiny' => $address_destiny,
                'lat' => $latitud_destiny,
                'lng' => $longitud_destiny,
                'created_at' => date("Y-m-d h:m:s"),
                'updated_at' => date("Y-m-d h:m:s")
            ]);
            $str_logTxt .= "RESPONSE_createNewTravel: " . json_encode($obj_Code) . ";\n";
            Log::debug($str_logTxt);
        }
        
       
        
        DB::table('travels')
        ->where('user_id', $obj_users->id)
        ->update(['address_destiny' => $new_destiny,
            'lat' => $latitud_destiny,
            'lng' => $longitud_destiny
        ]);
        
        $str_logTxt .= "RESPONSE_destinyUser2: " . json_encode($obj_destinyUser) . ";\n";
        Log::debug($str_logTxt);
        //direccion_evento
        $address_event=$obj_eventPlace->address;
        $latitud_evento=$obj_eventPlace->lat;
        $longitud_evento=$obj_eventPlace->lng;
        $radio=$obj_eventPlace->radio;
        
        $obj_validate_originDistance = Event::getDistanceOrigin($latitud_evento, $longitud_evento, $radio,$obj_eventPlace->id);
        $obj_validate_destinyDistance = Travel::getDistanceDestiny($latitud_evento, $longitud_evento, $radio,$obj_user->id);
        
        
        if(empty($obj_validate_originDistance) && empty($obj_validate_destinyDistance)){
            $arr_response = ['message' => 'La direccion de origen o destino no se encuentra dentro del rango del evento, imposible redimir codigo'];
        }
        else  {
            if ($obj_eventPlace->state =="inactive") {
                $arr_response = ['message' => 'El codigo ingresado no esta disponible en estos momentos'];
            }
            else{
                $arr_response = ['message' => 'Direccion origen o destino aceptada, codigo valido para realizar el viaje'];
                }
            }
        }
        
        Log::debug($str_logTxt);
        
        if(!empty($obj_users) && !empty($obj_destinyUser)){
            $arreglo =[$arr_response,$obj_eventPlace,$obj_user,$obj_destinyUser];
        }
        else{
            $arreglo =[$arr_response];
        }
        return json_encode($arreglo);
        
        
        
        
    }
    
}