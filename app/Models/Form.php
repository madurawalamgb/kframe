<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Form extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','dependencies','description','identifier'
    ];

    protected $appends = [
        'table'
    ];

    protected $casts = [
        'dependencies' => 'array', 
    ];

    public function fields(): HasMany
    {
        return $this->hasMany(Field::class);
    }

    /**
     * Get the user's first name.
     */
    protected function table(): Attribute
    {
        return Attribute::make(
            get: fn (string |null $value) => Str::snake(Str::plural(str_replace(' ', '', ucwords($this->name)))),
        );
    }

}
