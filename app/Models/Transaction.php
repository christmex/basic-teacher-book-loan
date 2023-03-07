<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use CrudTrait;
    use HasFactory;

    protected $fillable = [
        'school_year_id',
        'semester_id',
        'member_id',
        'book_id',
        'qty',
        'loaned_at',
        'returned_at',
    ];

    public function SchoolYear()
    {
        return $this->belongsTo('App\Models\SchoolYear', 'school_year_id','id');
    }

    public function Semester()
    {
        return $this->belongsTo('App\Models\Semester', 'semester_id','id');
    }
    public function Member()
    {
        return $this->belongsTo('App\Models\Member', 'member_id','id');
    }
    public function Book()
    {
        return $this->belongsTo('App\Models\Book', 'book_id','id');
    }
}
