<?php

namespace App\Http\Controllers\Admin;

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
        CRUD::setEntityNameStrings('transaction', 'transactions');
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
        CRUD::removeButtons(['update','delete','show']);

        CRUD::addColumn([
            'name'      => 'row_number',
            'type'      => 'row_number',
            'label'     => '#',
            'orderable' => false,
        ])->makeFirstColumn();
        
        CRUD::addColumn([
            'name'      => 'Book.book_cover', // The db column name
            'label'     => 'Book Cover', // Table column heading
            'type'      => 'image',
            'disk'   => 'public', 
            'height' => '130px',
            'width'  => '130px',
        ],);
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
       
        CRUD::column('member_id');
        CRUD::column('book_id');
        CRUD::column('qty');
        CRUD::column('loaned_at');
        CRUD::column('returned_at');
        CRUD::column('description');

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

        CRUD::addField([
            'type' => 'select',
            'label' => 'Active School Year',
            'name' => 'school_year_id', // the relationship name in your Migration
            'entity' => 'Schoolyear', // the relationship name in your Model
            'attribute' => 'school_year_name',
            'options'   => (function ($query) {
                return $query->where('is_Active', 1)->get();
            }), 
        ]);
        CRUD::addField([
            'type' => 'select',
            'label' => 'Active Semester',
            'name' => 'semester_id', // the relationship name in your Migration
            'entity' => 'Semester', // the relationship name in your Model
            'attribute' => 'semester_name',
            'options'   => (function ($query) {
                return $query->where('is_Active', 1)->get();
            }), 
        ]);
        CRUD::field('member_id');
        // CRUD::field('book_id');
        CRUD::addField([
            'type' => 'select_multiple',
            // 'allows_multiple' => true,
            'multiple' => true,
            'label' => 'Book',
            'name' => 'book_id', // the relationship name in your Migration
            'entity' => 'Book', // the relationship name in your Model
            'attribute' => 'book_name',
            'options'   => (function ($query) {
                return $query->where('book_stock','>=', 1)->get();
            }), 
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
        CRUD::field('qty')->attributes(['min' => 1])->default(1);
        CRUD::field('loaned_at')->value(now());
        
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
        CRUD::field('returned_at')->value(now());
        CRUD::modifyField('book_id',[
            'attributes' => [
                'readonly' => 'readonly',
            ]
        ]);
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
        CRUD::modifyField('member_id',[
            'attributes' => [
                'readonly' => 'readonly',
            ]
        ]);
    }

    

}
