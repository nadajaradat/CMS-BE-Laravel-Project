<?php

namespace App\Models\Doctor;

use App\Models\Department\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $table = 'doctors';
    protected $fillable = ['user_id', 'department_id', 'description', 'income_percentage'];
    protected $hidden = ['created_at', 'updated_at'];

    public function User()
    {
        return $this->belongsTo(User::class);
    }

    public function Department()
    {
        return $this->belongsTo(Department::class);
    }
}
