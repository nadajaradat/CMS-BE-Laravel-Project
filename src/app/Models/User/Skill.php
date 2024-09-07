<?php

namespace App\Models\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $table = 'skill';
    protected $fillable = ['user_id', 'skill_name', 'proficiency'];
    protected $hidden = ['created_at', 'updated_at'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
