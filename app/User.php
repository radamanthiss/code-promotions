<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class User extends Model
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code_id','name', 'email','state','payment_method','ubicacion_actual','lat','lng'
    ];
    
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'created_at','updated_at'
    ];
    
    public static function getOrigin($id) {
        $str_logTxt = __CLASS__ . "->" . __FUNCTION__ . "::";
        $str_logTxt .= "REQUEST::id:$id;";
        
        $arr_user = DB::select("SELECT ubicacion_actual FROM users where id=:id", ['id' => $id]);
        
        $str_logTxt .= "RESPONSE_getUserOrigin" . json_encode($arr_user);
        Log::debug($str_logTxt);
        
        return current($arr_user);
        
        
        
    }
    public static function getUbicationUser($code_id){
        $str_logTxt = __CLASS__ . "->" . __FUNCTION__ . "::";
        $str_logTxt .= "REQUEST_GET_UBICATION_USER::code_id:$code_id;";
        
        $arr_ubicUser = DB::select("SELECT * FROM users where code_id=:code_id", ['code_id' => $code_id]);
        
        $str_logTxt .= "RESPONSE_getUbicationUser" . json_encode($arr_ubicUser);
        Log::debug($str_logTxt);
        
        return current($arr_ubicUser);
        
    }
    
}
