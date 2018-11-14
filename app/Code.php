<?php
namespace App;

use Illuminate\Database\Eloquent\Model;



class Code extends Model
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'starts_on', 'ends_on', 'coupon_type','state','quantity_travel'
    ];
    
    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['created_at','updated_at'];
    
}