<?php

namespace App\Models\User;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    use HasFactory;
    protected $table = 'website';
    protected $fillable = ['user_id', 'url', 'icon', 'description'];
    protected $hidden = ['created_at', 'updated_at'];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
