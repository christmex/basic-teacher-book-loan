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
        'description',
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function($obj) {
            // check if the book_id exist
            if($obj->book_id){
                // find the book data by id
                $find = Book::find($obj->book_id);
                // Kalau buku ada stock boleh pinjam
                if($find->book_stock > 0 && $find->book_stock - $obj->qty >= 0){
                    $find->book_stock = $find->book_stock - $obj->qty;
                    $find->save();
                }
            }
        });

        
    }

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
