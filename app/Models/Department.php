<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class,'department_user','department_id','user_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
