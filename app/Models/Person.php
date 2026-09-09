<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Person extends Model
{
    //
    protected $fillable = [
        'name' ,
        'age',
    ];

    public function conatcts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }
}
