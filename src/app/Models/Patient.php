<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'national_id',
        'name',
        'phone',
        'date_of_birth',
        'gender',
        'uhid',
        'medical_history',
    ];

    
    public function isCreated(): bool
    {
        return $this->exists;
    }
}
