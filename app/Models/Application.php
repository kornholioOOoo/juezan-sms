<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $table = 'applications';
    protected $primaryKey = 'application_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'applicant_id', 
        'scholarship_id',
        'status',
        'remarks'
    ];


    public function student()
    {
        return $this->belongsTo(Student::class, 'applicant_id', 'student_id');
    }


    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class, 'scholarship_id', 'scholarship_id');
    }
}