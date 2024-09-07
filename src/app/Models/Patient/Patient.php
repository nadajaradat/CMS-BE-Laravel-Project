<?php

namespace App\Models\Patient;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $table = 'patients';
    protected $fillable = [
        'national_id',
        'name',
        'phone',
        'date_of_birth',
        'gender',
        'uhid',
        'medical_history',
    ];
    protected $hidden = ['created_at', 'updated_at'];

    
    public function isCreated(): bool
    {
        return $this->exists;
    }
}
