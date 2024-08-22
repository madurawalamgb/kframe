<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    protected $table = 'tests';
    
    protected $fillable = [
        'first_name','last_name','address'
    ];

    // Add your custom methods and properties here
}