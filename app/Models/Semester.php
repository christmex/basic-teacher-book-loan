<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'semester_name',
        'is_active'
    ];

    public function setSemesterNameAttribute($value)
    {
        $this->attributes['semester_name'] = ucwords($value);
    }
}
