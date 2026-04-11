<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scholarship extends Model
{
    use HasFactory;

    protected $table = 'scholarships';

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


    public function getRouteKeyName()
    {
        return 'scholarship_id';
    }
}