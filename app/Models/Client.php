<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $table = 'clients';
    
    protected $fillable = [
        'name','qualification','age','higher_than','gender'
    ];

    protected $cast = [
        'higher_than' => 'boolean'
    ];

    //static_arrays
    private static $Gender =  ['F' => 'Female', 'M' => 'Male']; 
 
	//static_functions
    public static function  getGender()
    {
        return self::$Gender;
    }

    public static function  decodeGender($key=null)
    {
        return $key ? (self::getGender()[$key]??null) : null;
    }

    

    // Add your custom methods and properties here
}