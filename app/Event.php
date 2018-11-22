<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Event extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code_id','name', 'address','city','place','lat','lng','radio'
    ];
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'created_at','updated_at'
    ];
    
    public static function getByDistance($lat, $lng, $radio,$code_id)
    {
        $results = DB::select(DB::raw('SELECT id, ( 6371 * acos( cos( radians(' . $lat . ') ) * cos( radians( lat ) ) * cos( radians( lng ) - radians(' . $lng . ') ) + sin( radians(' . $lat .') ) * sin( radians(lat) ) ) ) AS distance FROM events where code_id='.$code_id.' HAVING distance < ' . $radio . ' ORDER BY distance') );
        return $results;
    }
    
    public static function getPlaceEvent($code_id){
        $str_logTxt = __CLASS__ . "->" . __FUNCTION__ . "::";
        $str_logTxt .= "RESQUEST_GET_EVENT_PLACE::event_by_code_id:$code_id;";
        
        $arr_event = DB::select("SELECT * FROM events where code_id=:code_id", ['code_id' => $code_id]);
        
        $str_logTxt .= "RESPONSE::" . json_encode($arr_event);
        Log::debug($str_logTxt);
        
        return current($arr_event);
    }
    
    public static function getDistanceOrigin($lat,$lng,$radio,$code_id) {
        $str_logTxt = __CLASS__ . "->" . __FUNCTION__ . "::";
        $str_logTxt .= "RESQUEST_GET_DISTANCE_DESTINY::LAT:$lat, LNG:$lng, RADIO:$radio, CODE_ID:$code_id;";
        
        $arr_distance = DB::select("SELECT id,code_id,ubicacion_actual, (6371 * ACOS( SIN(RADIANS(lat)) * SIN(RADIANS($lat)) + COS(RADIANS(lng -  $lng)) * COS(RADIANS(lat)) * COS(RADIANS($lat)) ) ) AS distance FROM users where code_id=$code_id HAVING distance < $radio ORDER BY distance ASC");
        $str_logTxt .= "RESPONSE::" . json_encode($arr_distance);
        Log::debug($str_logTxt);
        
        return current($arr_distance);
        
    }
    
    public static function getPlacebyCode($code) {
        $str_logTxt = __CLASS__ . "->" . __FUNCTION__ . "::";
        $str_logTxt .= "RESQUEST_GET_PLACE_BY_CODE::event_by_code:$code;";
        
        $arr_event = DB::select("SELECT c.id,c.code,c.state,c.quantity_travel,c.starts_on,c.ends_on,e.name,e.address,e.lat,e.lng,e.radio FROM codes c LEFT JOIN events e on c.id = e.code_id where c.code=:code", ['code' => $code]);
        
        $str_logTxt .= "RESPONSE::" . json_encode($arr_event);
        Log::debug($str_logTxt);
        
        return current($arr_event);
        
    }
}