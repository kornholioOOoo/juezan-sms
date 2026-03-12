<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'user_id',
        'student_no',
        'first_name',
        'middle_name',
        'last_name',
        'course',
        'year_level',
        'contact_no',
        'address',
        'created_at',
        'updated_at'
    ];
}
