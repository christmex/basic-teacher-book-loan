<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\HasUploadFields;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use CrudTrait;
    use HasFactory;
    use HasUploadFields;

    protected $fillable = [
        'book_name',
        'book_sku',
        'book_stock',
        'book_cover',
    ];

    public static function boot()
    {
        parent::boot();
        static::deleting(function($obj) {
            if($obj->book_cover){
                \Storage::disk('public')->delete($obj->book_cover);
            }
        });
    }

    public function setBookNameAttribute($value)
    {
        $this->attributes['book_name'] = ucwords($value);
    }

    public function setBookCoverAttribute($value)
    {
        $attribute_name = "book_cover";
        $disk = "public";
        $destination_path = "book_cover";

        $this->uploadFileToDisk($value, $attribute_name, $disk, $destination_path, $fileName = null);

    // return $this->attributes[{$attribute_name}]; // uncomment if this is a translatable field
    }

    public function bookHistory(){
        return $this->hasMany('App\Models\BookHistory', 'book_id','id');
    }
}
