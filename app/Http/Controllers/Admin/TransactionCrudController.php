<?php

namespace App\Http\Controllers\Admin;

use App\Models\Book;
use Illuminate\Support\Facades\DB;
use Backpack\CRUD\app\Library\Widget;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\TransactionRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class TransactionCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class TransactionCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Transaction::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/transaction');
        CRUD::setEntityNameStrings('Peminjaman Buku','Daftar Peminjaman Buku');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        
        CRUD::with(['Book']);
        CRUD::orderBy('returned_at','asc');

        CRUD::removeButtons(['show','update']);

        if(!request('show_delete_button')){
            CRUD::removeButtons(['delete']);
        }else {
            // CRUD::removeButtons(['delete','show','update']);
        }
        
        // if(request('filterShowAll') || request('filterReturned')){
        //     Widget::add([
        //         'type'         => 'alert',
        //         'class'        => 'alert alert-info mb-2',
        //         'content'      => 'Filter sedang aktif',
        //         'close_button' => true, // show close button or not
        //     ]);
        // }
        // if(request('filterUnreturn')){
        //     CRUD::addClause('where','returned_at',NULL);
        // }
        // if(request('filterReturned')){
        //     CRUD::addClause('where','returned_at','!=',NULL);
        // }

        if(request('filterShowAll')){
            Widget::add([
                'type'         => 'alert',
                'class'        => 'alert alert-success mb-2',
                'content'      => 'Ini adalah daftar semua peminjaman, baik yang sudah dikembalikan maupun sedang dipinjam.<br> Untuk melihat daftar peminjaman yang sedang berjalan saja silahkan klik tombol <strong>set ulang</strong> di bagian atas 👆',
                'close_button' => true, // show close button or not
            ]);
        }else{
            Widget::add([
                'type'         => 'alert',
                'class'        => 'alert alert-success mb-2',
                'content'      => 'Ini adalah daftar semua peminjaman yang belum dikembalikan <br> Untuk melihat semua peminjaman, silahkan klik <strong>Tampilkan semua 👇</strong>',
                'close_button' => true, // show close button or not
            ]);
            CRUD::addClause('where','returned_at',NULL);
        }


        // CRUD::addButtonFromModelFunction('top', 'filterUnreturn', 'filterUnreturn', 'beginning');
        // CRUD::addButtonFromModelFunction('top', 'filterReturned', 'filterReturned', 'beginning');
        CRUD::addButtonFromModelFunction('top', 'filterShowAll', 'filterShowAll', 'end');
        // CRUD::button('create')->makeFirst();

        CRUD::addColumn([
            'name'      => 'row_number',
            'type'      => 'row_number',
            'label'     => '#',
            'orderable' => false,
        ])->makeFirstColumn();
        CRUD::addColumn([
            'name'      => 'Book.book_cover', // The db column name
            'label'     => 'Foto Buku', // Table column heading
            'type'      => 'image',
            'disk'   => 'public', 
            'height' => '130px',
            'width'  => '130px',
        ],);
        // CRUD::addColumn([
        //     'name'      => 'Book.book_name', // The db column name
        //     'label'     => 'Book Title', // Table column heading
        //     'type'      => 'select',
        // ],);
        CRUD::addColumn([
            "label" => "Judul Buku",
            "type" => "select",
            "name" => "book_id",
            "entity" => "Book",
            "attribute" => "book_name",
            "model" => "App\Models\Book",
            "limit" => 1000,
        ]);
        // CRUD::column('book_id');
        CRUD::column('member_id')->limit(1000)->label('Nama Guru');
        // CRUD::addColumn([
        //     'name'      => 'member_id', // The db column name
        //     'label'     => 'Member', // Table column heading
        //     'type'      => 'custom_html',
        //     'value'      => function($entry) {
        //         if(!empty($entry->returned_at)){
        //             return $entry->member->member_name.'<br><span class="badge badge-success">Dikembalikan '.$entry->returned_at->diffForHumans().'<span>';
        //         }
        //         return $entry->member->member_name;
        //     },
        // ],);
        CRUD::addColumn([
            "name" => "school_year_id",
            "label" => "Tahun Ajaran",
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
       
        // CRUD::column('book_id');
        CRUD::column('qty')->label('Jumlah Buku');
        // CRUD::column('loaned_at');
        CRUD::addColumn([
            'name' => 'loaned_at',
            'label' => 'Dipinjam Pada',
            'type'      => 'custom_html',
            'value'      => function($entry) {
                if(!empty($entry->loaned_at)){
                    return '<span class="badge badge-secondary">'.$entry->loaned_at->diffForHumans().'<span>';
                }
            },
        ]);
        CRUD::addColumn([
            'name' => 'returned_at',
            'label' => 'Dikembalikan Pada',
            'type'      => 'custom_html',
            'value'      => function($entry) {
                if(!empty($entry->returned_at)){
                    return '<span class="badge badge-success">'.$entry->returned_at->diffForHumans().'<span>';
                }
            },
        ]);
        
        CRUD::column('description')->limit(1000)->label('Deskripsi');
        CRUD::setOperationSetting('lineButtonsAsDropdown', true);
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
        CRUD::setValidation(TransactionRequest::class);
        Widget::add([
            'type'         => 'alert',
            'class'        => 'alert alert-danger mb-2',
            'heading'      => 'Please remember',
            'content'      => 'Sebelum guru meminjam buku, cek dahulu apakah ada buku yang belum dikembalikan',
            'close_button' => true, // show close button or not
        ]);
        // dd($this->crud->route);
        
        // $this->crud->route
        // request()->fullUrlWithQuery(['bar' => 'baz']);
        CRUD::removeSaveAction('save_and_new');
        $this->crud->addSaveAction([
            'name' => 'save_with_current_user',
            'button_text' => 'Save and new item',
            'redirect' => function($crud, $request, $itemId) {
                // ?member_id='.request()->has('member_id') ? request('member_id') : 1
                return $crud->route.'/create?member_id='.$request->member_id;
            }, // what's the redirect URL, where the user will be taken after saving?
        ]);        
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
        // CRUD::field('member_id')->default(request()->has('member_id') ? request('member_id') : 1)->label('Nama Guru');
        CRUD::addField([
            'name' => 'member_id',
            'type' => 'livewire_select',
            'label' => 'Nama Guru',
            'hint' => 'Contoh: Pinta',
            'attribute' => 'member_name',
            'default' => request()->has('member_id') ? request('member_id') : NULL,
            'model' => \App\Models\Member::class,
            'is_book' => false,
            'attributes' => [
                'autocomplete' => 'off'
            ],
        ]);


        // CRUD::field('book_id');
        // CRUD::addField([
        //     'type' => 'select',
        //     // 'model' => "App\Models\Book",
        //     // 'allows_multiple' => true,
        //     'multiple' => true,
        //     'label' => 'Nama Buku',
        //     'name' => 'book_id', // the relationship name in your Migration
        //     'entity' => 'Book', // the relationship name in your Model
        //     'attribute' => 'book_name',
        //     'options'   => (function ($query) {
        //         return $query->where('book_stock','>=', 1)->get();
        //     }), 
        // ]);
        CRUD::addField([
            'name' => 'book_id',
            'type' => 'livewire_select',
            'label' => 'Nama Buku',
            'hint' => 'Contoh: Tematik',
            'attribute' => 'book_name',
            'model' => \App\Models\Book::class,
            'is_book' => true,
            'show_image' => 'book_cover',
            'attributes' => [
                'autocomplete' => 'off'
            ],
        ]);

        // CRUD::field('comments')->subfields([['name' => 'body']]);
        // CRUD::addField([
        //     'type' => 'checklist',
        //     'label' => 'Book',
        //     'name' => 'book_id', // the relationship name in your Migration
        //     'entity' => 'Book', // the relationship name in your Model
        //     'attribute' => 'book_name',
        //     // 'options'   => (function ($query) {
        //     //     return $query->where('book_stock','>=', 1)->get();
        //     // }), 
        //     // 'model'     => "\App\Models\Book",
        //     'pivot'     => false,
        //     'number_of_columns' => 3,
        // ]);
        CRUD::field('qty')->attributes(['min' => 1])->default(1)->label('Jumlah yang dipinjam');
        CRUD::field('loaned_at')->type('datetime')->value(now())->label('Dipinjam Pada?');
        
        CRUD::field('description')->type('textarea')->hint('Jika ada catatan untuk peminjaman ini, jika tidak ada tidak usah di isi')->label('Deskripsi');

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
        // CRUD::field('returned_at')->value(now());

        CRUD::modifyField('qty',[
            'attributes' => [
                'readonly' => 'readonly',
            ]
        ]);
        CRUD::modifyField('loaned_at',[
            'attributes' => [
                'readonly' => 'readonly',
            ]
        ]);
        CRUD::modifyField('semester_id',[
            'attributes' => [
                'readonly' => 'readonly',
            ]
        ]);
        CRUD::modifyField('school_year_id',[
            'attributes' => [
                'readonly' => 'readonly',
            ]
        ]);
        // CRUD::modifyField('member_id',[
        //     'attributes' => [
        //         'readonly' => 'readonly',
        //     ]
        // ]);
        // CRUD::modifyField('book_id',[
        //     'attributes' => [
        //         'readonly' => 'readonly',
        //     ]
        // ]);
    }




    // Bulk transaction section
    public function bulkReturnBook()
    {
        $this->crud->hasAccessOrFail('create');
        
        $querySelectTransaction = $this->getSelectedTransaction($this->crud->getRequest()->input('entries'));

        // Cek jika data ada
        if(count($querySelectTransaction) < 1){
            return Response()->json([
                'error' => "the selected transaction entries already returned"
            ], 500); // Status code here
            
        }

        DB::beginTransaction();
        try {
            // update book stock here
            foreach ($querySelectTransaction as $key => $value) {
                $value->update(['returned_at' => date('Y-m-d H:i:s')]);
                Book::find($value->book_id)->increment('book_stock',$value->qty);
            }
            DB::commit();
            return true;
        } catch (\Throwable $th) {
            $th->getMessage();
            DB::rollback();
        }
    }

    protected function getSelectedTransaction($entries){
        return $this->crud->model->where('returned_at','=',null)->where(function($query) use($entries) {
            $query->whereIn('id', $entries);
        })->get();
    }



    protected function setupBulkReturnBookRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/bulk-return-book', [
            'as'        => $routeName.'.bulkReturnBook',
            'uses'      => $controller.'@bulkReturnBook',
            'operation' => 'bulkReturnBook',
        ]);
    }

    protected function setupBulkReturnBookDefaults()
    {
        $this->crud->allowAccess('bulkReturnBook');

        $this->crud->operation('list', function () {
            $this->crud->enableBulkActions();
            $this->crud->addButton('bottom', 'bulk_returned_book', 'view', 'bulk_returned_book', 'beginning');
        });
    }

    

}
