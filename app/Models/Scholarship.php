<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    use HasFactory;

    
     protected $fillable = [
        'scholarship_name',
        'provider',
        'description',
        'amount',
        'slots',
        'status'
    ];
}
