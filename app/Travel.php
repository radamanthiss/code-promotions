<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Travel extends Model
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','total_value', 'address_destiny','lat','lng'
    ];
    
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'created_at','updated_at'
    ];
    
   
    
    public static function getDestiny($user_id){
        
        
        $str_logTxt = __CLASS__ . "->" . __FUNCTION__ . "::";
        $str_logTxt .= "RESQUEST_TRAVELS::user_id:$user_id;";
        
        $arr_user_travel = DB::select("SELECT address_destiny,lat,lng FROM travels where user_id=:user_id", ['user_id' => $user_id]);
        
        $str_logTxt .= "RESPONSE::" . json_encode($arr_user_travel);
        Log::debug($str_logTxt);
        
        return current($arr_user_travel);
        
    }
    
    public static function getDistanceDestiny($lat,$lng,$radio,$user_id) {
        
        $str_logTxt = __CLASS__ . "->" . __FUNCTION__ . "::";
        $str_logTxt .= "RESQUEST_GET_DISTANCE_ORIGIN::LAT:$lat, LNG:$lng, RADIO:$radio;";
        
        $arr_distance = DB::select("SELECT id,user_id,total_value,address_destiny, (6371 * ACOS( SIN(RADIANS(lat)) * SIN(RADIANS($lat)) + COS(RADIANS(lng -  $lng)) * COS(RADIANS(lat)) * COS(RADIANS($lat)) ) ) AS distance FROM travels WHERE user_id=$user_id HAVING distance < $radio ORDER BY distance ASC");
        $str_logTxt .= "RESPONSE::" . json_encode($arr_distance);
        Log::debug($str_logTxt);
        
        return current($arr_distance);
        
    }
}