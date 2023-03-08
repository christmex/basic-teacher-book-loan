<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_year_id',
        'semester_id',
        'book_id',
        'qty',
        'description',
        'operation',
        'book_stock_added_at',
    ];


    // public function SchoolYear()
    // {
    //     return $this->belongsTo('App\Models\SchoolYear', 'school_year_id','id');
    // }

    // public function getActiveSchoolYear(){
    //     return SchoolYear::where('is_active',1)->first();
    // }

    // public function Semester()
    // {
    //     return $this->belongsTo('App\Models\Semester', 'semester_id','id');
    // }
    // public function Book()
    // {
    //     return $this->belongsTo('App\Models\Book', 'book_id','id');
    // }
}
