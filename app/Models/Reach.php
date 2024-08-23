<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reach extends Model
{
    protected $table = 'reaches';
    
    protected $fillable = [
        'name','count','description'
    ];

    // Add your custom methods and properties here
}