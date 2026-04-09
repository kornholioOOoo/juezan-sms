<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $table = 'students';
    protected $primaryKey = 'student_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'first_name',
        'middle_name',
        'last_name',
        'course',
        'year_level',
        'contact_no',
        'address'
    ];

    /**
     * Customize the route key name
     */
    public function getRouteKeyName()
    {
        return 'student_id';
    }

    /**
     * Relationship: Student belongs to a User
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    /**
     * Relationship: Student has many Applications
     */
    public function applications()
    {
        return $this->hasMany(Application::class, 'applicant_id', 'student_id');
    }
}