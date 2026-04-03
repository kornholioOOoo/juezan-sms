<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    use HasFactory;

    protected $table = 'scholarships';

    // ✅ THIS FIXES YOUR ERROR
    protected $primaryKey = 'scholarship_id';

    public $incrementing = true;

    protected $keyType = 'int';

    protected $fillable = [
        'scholarship_name',
        'provider',
        'description',
        'amount',
        'slots',
        'status'
    ];

    // Optional (for route model binding)
    public function getRouteKeyName()
    {
        return 'scholarship_id';
    }
}