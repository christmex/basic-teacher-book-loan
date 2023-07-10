<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BookLackRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BookLackCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BookLackCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\BookLack::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/book-lack');
        CRUD::setEntityNameStrings('book lack', 'book lacks');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::removeButtons(['show']);
        if(!request('show_delete_button')){
            CRUD::removeButtons(['delete']);
        }
        CRUD::setEntityNameStrings('Kekurangan Buku','Daftar Kekurangan Buku');
        CRUD::addColumn([
            "name" => "school_year_id",
            "label" => "School Year",
            "entity" => "SchoolYear",
            "model" => "App\Models\SchoolYear",
            "type" => "select",
            "attribute" => "school_year_name"
        ]);
        CRUD::addColumn([
            "name" => "semester_id",
            "label" => "Semester",
            "entity" => "Semester",
            "model" => "App\Models\Semester",
            "type" => "select",
            "attribute" => "semester_name"
        ]);
        CRUD::column('member_id')->limit(1000);
        // CRUD::column('book_id')->limit(1000);
        // CRUD::column('book_name')->limit(1000);
        // CRUD::column('qty');
        CRUD::column('description')->limit(1000);

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(BookLackRequest::class);

        CRUD::setEntityNameStrings('Kekurangan Buku','Kekurangan Buku');
        CRUD::addField([
            'type' => 'select',
            'label' => 'Tahun ajaran yang sedang berjalan',
            'name' => 'school_year_id', // the relationship name in your Migration
            'entity' => 'Schoolyear', // the relationship name in your Model
            'attribute' => 'school_year_name',
            'options'   => (function ($query) {
                return $query->where('is_Active', 1)->get();
            }), 
        ]);
        CRUD::addField([
            'type' => 'select',
            'label' => 'Semester yang sedang berjalan',
            'name' => 'semester_id', // the relationship name in your Migration
            'entity' => 'Semester', // the relationship name in your Model
            'attribute' => 'semester_name',
            'options'   => (function ($query) {
                return $query->where('is_Active', 1)->get();
            }), 
        ]);
        CRUD::addField([
            'name' => 'member_id',
            'type' => 'livewire_select',
            'label' => 'Nama Guru',
            'hint' => 'Contoh: Pinta',
            'attribute' => 'member_name',
            'model' => \App\Models\Member::class,
            'is_book' => false,
            'attributes' => [
                'autocomplete' => 'off'
            ],
        ]);
        // CRUD::addField([
        //     'name' => 'book_id',
        //     'type' => 'livewire_select',
        //     'label' => 'Cari Buku Yang Sudah Ada',
        //     'hint' => 'Contoh: Tematik, jika data buku belum ada, silahkan menggunakan isian yg dibawah',
        //     'attribute' => 'book_name',
        //     'model' => \App\Models\Book::class,
        //     'is_book' => true,
        //     'attributes' => [
        //         'autocomplete' => 'off'
        //     ],
        // ]);

        // CRUD::field('member_id')->default(request()->has('member_id') ? request('member_id') : 1)->label('Nama Guru');
        // CRUD::addField([
        //     'type' => 'select',
        //     'multiple' => true,
        //     'label' => 'Nama Buku',
        //     'name' => 'book_id', // the relationship name in your Migration
        //     'entity' => 'Book', // the relationship name in your Model
        //     'attribute' => 'book_name',
        //     'options'   => (function ($query) {
        //         return $query->where('book_stock','>=', 1)->get();
        //     }), 
        // ]);
        // CRUD::field('book_name')->label('Judul Buku')->hint('Buku yang belum terdata, silahkan masukkan judul buku');
        // CRUD::field('qty')->attributes(['min' => 1])->default(1)->label('Jumlah yang kurang');
        CRUD::field('description');

        /**
         * Fields can be defined using the fluent syntax or array syntax:
         * - CRUD::field('price')->type('number');
         * - CRUD::addField(['name' => 'price', 'type' => 'number'])); 
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
