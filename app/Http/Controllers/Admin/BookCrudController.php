<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BookRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use League\CommonMark\Extension\Attributes\Node\Attributes;

/**
 * Class BookCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BookCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    use \App\Http\Controllers\Admin\Operations\AddBookStockOperation;
    use \App\Http\Controllers\Admin\Operations\RemoveBookStockOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Book::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/book');
        CRUD::setEntityNameStrings('book', 'books');

    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setEntityNameStrings('Buku','Daftar Buku');
        // CRUD::setOperationSetting('showDeleteButton', true);
        CRUD::column('book_name')->label('Nama Buku')->limit(255);
        CRUD::addColumn([
            'name'      => 'book_cover', // The db column name
            'label'     => 'Foto Buku', // Table column heading
            'type'      => 'image',
            // 'prefix' => 'folder/subfolder/',
            // image from a different disk (like s3 bucket)
            'disk'   => 'public', 
            // optional width/height if 25px is not ok with you
            'height' => '130px',
            'width'  => '130px',
            'attributes' => [
                'accept' => 'image/*;capture=camera'
            ]
        ]);
        CRUD::column('book_stock')->label('Stok Buku Yang Tersedia');
        CRUD::addColumn([
            "label" => "Total buku yang dipinjam",
            "type" => "relationship_count",
            "name" => "getBookLoanned",
        ]);
        CRUD::addColumn([
            "label" => "Sedang dipinjam oleh",
            "type" => "select",
            "name" => "loaned_by",
            "entity" => "getBookLoanned.Member",
            "attribute" => "member_name",
            "limit" => 1000,
            "model" => "App\Models\Transaction",
        ]);
        
        
        // CRUD::column('book_sku')->label('Barcode Buku');
        // CRUD::column('book_cover');
        CRUD::column('description');

        /**
         * Columns can be defined using the fluent syntax or array syntax:
         * - CRUD::column('price')->type('number');
         * - CRUD::addColumn(['name' => 'price', 'type' => 'number']); 
         */
        CRUD::setOperationSetting('lineButtonsAsDropdown', true);
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(BookRequest::class);

        CRUD::field('book_name')->label('Nama Buku');
        
        CRUD::field('book_stock')->attributes(['min' => 1])->default(1)->label('Stok Buku');
        // CRUD::field('book_sku')->label('Barcode Buku')->hint('Masukkan barcode buku');
        // CRUD::field('book_cover')->type('file');
        CRUD::addField(
            [   // Upload
                'name'      => 'book_cover',
                'label'     => 'Foto Buku',
                'type'      => 'upload',
                'upload'    => true,
                // 'disk'      => 'public', // if you store files in the /public folder, please omit this; if you store them in /storage or S3, please specify it;
                // optional:
                'temporary' => 10 // if using a service, such as S3, that requires you to make temporary URLs this will make a URL that is valid for the number of minutes specified
            ],
        );
        CRUD::field('description')->type('textarea');

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
        CRUD::modifyField('book_stock',[
            'attributes' => [
                'readonly' => 'readonly',
            ]
        ]);
        // CRUD::setOperationSetting('showDeleteButton', true);
    }

    protected function setupShowOperation(){
        $this->crud->setOperationSetting('tabsEnabled', true);
        // $this->crud->setOperationSetting('tabsType', 'horizontal');
        // $this->setupListOperation();
        CRUD::column('book_name')->tab('Book Data');
        CRUD::column('book_stock')->tab('Book Data');
        CRUD::column('book_sku')->tab('Book Data');
        $this->crud->addColumn([
            'name' => 'book_history_desc',
            'label' => 'Book History Desc',
            'type' => 'select',
            'entity' => 'bookHistory',
            'attribute' => 'description',
            'model' => 'App\Models\BookHistory',
            'tab' => 'Book History',
            'limit' => 1000,
        ]);
        $this->crud->addColumn([
            'name' => 'book_history_qty',
            'label' => 'Book History QTY',
            'type' => 'select',
            'entity' => 'bookHistory',
            'attribute' => 'qty',
            'model' => 'App\Models\BookHistory',
            'tab' => 'Book History',
        ]);
        $this->crud->addColumn([
            'name' => 'book_history_date',
            'label' => 'Book History Date',
            'type' => 'select',
            'entity' => 'bookHistory',
            'attribute' => 'book_stock_added_at',
            'model' => 'App\Models\BookHistory',
            'tab' => 'Book History',
        ]);
    }
}
