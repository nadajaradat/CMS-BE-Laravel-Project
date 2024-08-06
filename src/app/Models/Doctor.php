<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'department_id', 'description', 'income_percentage'];

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function Department()
    {
        return $this->belongsTo(Department::class);
    }
}
