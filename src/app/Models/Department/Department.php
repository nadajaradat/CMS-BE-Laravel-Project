<?php

namespace App\Models\Department;

use App\Models\Doctor\Doctor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';
    protected $fillable = ['name', 'description', 'is_active'];
    protected $hidden = ['created_at', 'updated_at'];

    public function Doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
