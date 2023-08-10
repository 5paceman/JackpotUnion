<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SyndicateSetting extends Model
{
    protected $guarded = [];
    protected $attributes = [ 
        'rules' => 'Please fill out your syndicate rules.',
    ];
}
