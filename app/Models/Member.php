<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'member_name'
    ];

    public function setMemberNameAttribute($value)
    {
        $this->attributes['member_name'] = ucwords($value);
    }
}
