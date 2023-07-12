<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
        'updated_at' => 'datetime:Y-m-d',
        'loaned_at' => 'datetime:Y-m-d',
        'returned_at' => 'datetime:Y-m-d',
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

        // static::updating(function($obj) {
        //     // Check if the book exist
        //     if($obj->book_id){

        //     }
        // });

        // do when delete the return back the stock
        static::deleting(function($obj) {
            // Check if the book exist
            if($obj->book_id){
                if($obj->returned_at == NULL){
                    $find = Book::find($obj->book_id);
                    $find->book_stock = $find->book_stock + $obj->qty;
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

    // public function setLoanedAtAttribute($value)
    // {
    //     $this->attributes['loaned_at'] = Carbon::parse($value)->format('Y-m-d H:i:s');
        
    // }
    // ->format('d-m-Y H:i:s');

    public function filterUnreturn()
    {
        return '<a class="btn btn-sm btn-link" href="?filterUnreturn=filterUnreturn" data-toggle="tooltip" title="Filter"><i class="la la-filter"></i> Filter Buku Yang Belum Kembali</a>';
    }
    public function filterReturned()
    {
        return '<a class="btn btn-sm btn-link" href="?filterReturned=filterReturned" data-toggle="tooltip" title="Filter"><i class="la la-filter"></i> Filter Buku Yang Sudah Kembali</a>';
    }

    public function filterShowAll()
    {
        return '<a class="btn btn-sm btn-link" href="?filterShowAll=filterShowAll" data-toggle="tooltip" title="Filter"><i class="la la-filter"></i> Tampilkan Semua</a>';
    }

}
