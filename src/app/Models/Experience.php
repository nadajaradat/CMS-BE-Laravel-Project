<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;
    protected $table = 'experience';
    protected $fillable = ['user_id', 'company_name', 'role', 'start_date', 'end_date', 'description'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
