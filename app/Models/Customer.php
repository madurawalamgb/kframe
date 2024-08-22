<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    
    protected $fillable = [
        'first_name','last_name','address'
    ];

    // Add your custom methods and properties here
}