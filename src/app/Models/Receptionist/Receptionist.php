<?php

namespace App\Models\Receptionist;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receptionist extends Model
{
    use HasFactory;

    protected $table = 'receptionists';
    protected $fillable = ['user_id'];
    protected $hidden = ['created_at', 'updated_at'];

    public function User()
    {
        return $this->belongsTo(User::class);
    }
}
