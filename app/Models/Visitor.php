<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = ['visitor_id','operator_id','ip','name','email','phone','status','messages'];

    protected $appends = [
        'avatar'
    ];

    public function getAvatarAttribute() {
        // your logic of how to get discount value
        return $this->attributes['avatar'] = 'https://eu.ui-avatars.com/api/?name='.$this->name.'&size=250';
    }

    public function messages()
    {
        return $this->hasMany(Message::class,'visitor_id');
    }
}
