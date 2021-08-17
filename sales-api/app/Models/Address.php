<?php

namespace App\Models;

class Address
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'street',
        'neighborhood',
        'city',
        'state',
        'postal_code',
    ];

}
