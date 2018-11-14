<?php


namespace App\Http\Controllers;

use App\Code;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CodeController extends Controller
{
    
    public function getCode($id) {
        $str_logTxt = __CLASS__ . "->" . __FUNCTION__ . "::";
        $str_logTxt .= "RESQUEST: code:$id;";
        
        //Busca codigo por id
        
        $obj_code =Code::find($id);
        
        //Identifica si el usuario existe y pertenece a la aplicacion
        if (empty($obj_code)) {
            $str_logTxt .= "RESPONSE: El codigo que intenta consultar no existe";
            return response()->json(['error' => 'El codigo que intenta consultar no existe'], Response::HTTP_FORBIDDEN);
        }
        
        $str_logTxt .= "RESPONSE: " . json_encode($obj_code) . ";";
        Log::debug($str_logTxt);
        return response()->json($obj_code);
    }
    
    public function getAllCode() {
        $str_logTxt = __CLASS__ . "->" . __FUNCTION__ . "::";
        $str_logTxt .= "RESQUEST:";
        
        //Busca codigo por id
        
        $obj_allcode =Code::all();
        
        //Identifica si el usuario existe y pertenece a la aplicacion
        if (empty($obj_allcode)) {
            $str_logTxt .= "RESPONSE: No existen codigos";
            return response()->json(['error' => 'No existen codigos disponibles'], Response::HTTP_FORBIDDEN);
        }
        
        $str_logTxt .= "RESPONSE: " . json_encode($obj_allcode) . ";";
        Log::debug($str_logTxt);
        return response()->json($obj_allcode);
    }
    
    public function createNewCode(Request $request) {
        $str_logTxt = __CLASS__ . "->" . __FUNCTION__ . "::";
        $str_logTxt .= "RESQUEST: " . json_encode($request->toArray()) . ";";
        try {
            //Busca si existe el codigo que se trata de crear
            $arrobj_Code = Code::where('code', $request->input('code'))
            ->where('coupon_type', $request->input('coupon_type'))
            ->get();
            
            if (!$arrobj_Code->isEmpty()) {
                $str_logTxt .= "RESPONSE_createCode: Codigo ya existe" . json_encode($arrobj_Code) . ";";
                return response()->json(['error' => 'El codigo que trata de generar ya existe.'], Response::HTTP_CONFLICT);
            }
            else {
                
                $obj_Code = DB::table('codes')->insert([
                    'code' => $request->code,
                    'starts_on' => $request->starts_on,
                    'ends_on' => $request->ends_on,
                    'coupon_type' => $request->coupon_type,
                    'state' => $request->state,
                    'quantity_travel' => $request->quantity_travel,
                    'created_at' => date("Y-m-d hh:mm:ss"),
                    'updated_at' => date("Y-m-d hh:mm:ss")
                ]);
            }
            
        } catch (\Exception $e) {
            $str_logTxt .= "[ERROR= " . $e->getMessage() . "];";
            Log::debug($str_logTxt);
            return response()->json(['error' => 'Error interno en servidor.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        $str_logTxt .= "RESPONSE_createNewCode: " . json_encode($obj_Code) . ";";
        Log::debug($str_logTxt);
        
        return response()->json($obj_Code);
    }
    public function getCodesActive() {
        $str_logTxt = __CLASS__ . "->" . __FUNCTION__ . "::";
        $str_logTxt .= "RESQUEST:";
        try {
            //Trae codigos activas
            $arr_activeCode = Code::where('state', 'active')
            ->get();
        }catch (\Exception $e) {
            $str_logTxt .= "[ERROR= " . $e->getMessage() . "];";
            Log::debug($str_logTxt);
            return response()->json(['error' => 'Error interno en servidor.'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        $str_logTxt .= "RESPONSE_getCodesActive: " . json_encode($arr_activeCode) . ";";
        Log::debug($str_logTxt);
        return response()->json($arr_activeCode);
    }
    
    public function deactiveCodes(Request $request) {
        $str_logTxt = __CLASS__ . "->" . __FUNCTION__ . "::";
        $str_logTxt .= "RESQUEST: " . json_encode($request->toArray()) . ";";
        
        try {
            //Valida si tiene parametros para actualizar
            if (!($request->exists('state'))) {
                $str_logTxt .= "RESPONSE: No se encontro ninguno de los parametros editables;";
                Log::debug($str_logTxt);
                
                return response()->json(['error' => 'No se encontro ninguno de los parametros editables'], Response::HTTP_BAD_REQUEST);
            }
            //Valida si existe codigo
            $obj_user = Code::find($request->id);
            if (is_null($obj_user)) {
                $str_logTxt .= "RESPONSE: El codigo que intenta modificar no existe;";
                Log::debug($str_logTxt);
                
                return response()->json(['error' => 'El codigo que intenta modificar no existe'], Response::HTTP_NOT_FOUND);
            }
            
            //Actualiza parametros en BD
            $obj_user->state = $request->input('state', $obj_user->state);
            $obj_user->save();
            
            $str_logTxt .= "RESPONSE: " . json_encode($obj_user) . ";";
            Log::debug($str_logTxt);
        } catch (\Exception $e) {
            $str_logTxt .= "[ERROR= " . $e->getMessage() . "];";
            Log::debug($str_logTxt);
            $res=array("id"=> 0,
                "code"=> "00000",
                "starts_on"=> "0000-00-00",
                "ends_on"=> "0000-00-00",
                "coupon_type"=> "",
                "is_active"=> "",
                "quantity_travel"=> "0",
                "created_at"=> "0000-00-00 00:00:00",
                "updated_at"=> "0000-00-00 00:00:00");
            return response()->json($res);
        }
        return response()->json($obj_user);
    }
    
    
}


