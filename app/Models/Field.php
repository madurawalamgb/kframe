<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Field extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_id','field','name','description','type','selections','readonly','disabled'
    ];

    protected $casts = [
       // 'selections' => 'array', 
    ];

     /**
     * Get and set the selections.
     */
    protected function selections(): Attribute
    {
        return Attribute::make(
            get: fn ( $value) => json_decode($value, true),
        );
    }
    
}
