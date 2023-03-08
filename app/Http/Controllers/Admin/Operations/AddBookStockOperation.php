<?php

namespace App\Http\Controllers\Admin\Operations;

use App\Models\Semester;
use App\Models\SchoolYear;
use App\Models\BookHistory;
use Prologue\Alerts\Facades\Alert;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\BookStockRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait AddBookStockOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupAddBookStockRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/{id}/add-book-stock', [
            'as'        => $routeName.'.addBookStock',
            'uses'      => $controller.'@addBookStock',
            'operation' => 'addBookStock',
        ]);

        Route::post($segment.'/add-book-stock/{id}', [
            'as'        => $routeName.'.addBookStock',
            'uses'      => $controller.'@PostaddBookStock',
            'operation' => 'addBookStock',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupAddBookStockDefaults()
    {
        CRUD::allowAccess('addBookStock');

        CRUD::operation('addBookStock', function () {
            CRUD::loadDefaultOperationSettingsFromConfig();
            $this->crud->enableInlineErrors();
            $this->crud->enableGroupedErrors();
            // CRUD::setupDefaultSaveActions();
        });

        CRUD::operation('list', function () {
            // CRUD::addButton('top', 'add_book_stock', 'view', 'crud::buttons.add_book_stock');
            CRUD::addButton('line', 'add_book_stock', 'view', 'crud::buttons.add_book_stock');
        });
    }

    protected function setupAddBookStockOperation(){

        // CRUD::addField([
        //     'type' => 'text',
        //     'label' => 'Active School Year',
        //     'name' => 'school_year_id', // the relationship name in your Migration
        //     // 'entity' => 'Schoolyear', // the relationship name in your Model
        //     // 'attribute' => 'school_year_name',
        //     'value'   => BookHistory::getActiveSchoolYear()
        // ]);
        // CRUD::addField([
        //     'type' => 'select',
        //     'label' => 'Active Semester',
        //     'name' => 'semester_id', // the relationship name in your Migration
        //     'entity' => 'Semester', // the relationship name in your Model
        //     'attribute' => 'semester_name',
        //     'options'   => (function ($query) {
        //         return $query->where('is_Active', 1)->get();
        //     }), 
        // ]);

        CRUD::setValidation(BookStockRequest::class);
        CRUD::field('book_name')->attributes(['readonly' => 'readonly','disabled' => 'disabled'])->value($this->crud->getCurrentEntry()->book_name);

        CRUD::field('previous_book_stock')->attributes(['readonly' => 'readonly','disabled' => 'disabled'])->value($this->crud->getCurrentEntry()->book_stock);
        
        CRUD::field('book_stock')->attributes(['min' => 0])->type('number');

        CRUD::field('book_stock_description')->type('textarea')->attributes([
            'placeholder' => 'Ex: Ambil buku baru dari dimas'
        ]);
        CRUD::field('book_stock_added_at')->value(now())->type('date');

        CRUD::addSaveAction([
            'name' => 'addBookStock',
            'redirect' => function ($crud, $request, $itemId) {
                return $crud->route;
            },
            'button_text' => 'Add Book Stock',
        ]);
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function addBookStock($id)
    {
        // CRUD::hasAccessOrFail('addBookStock');

        // $id = $this->crud->getCurrentEntryId() ?? $id;

        // // get data based on model
        // $this->data['entry'] = $this->crud->getEntryWithLocale($id);

        // // Set the data if there is exist
        // $this->crud->setOperationSetting('fields', $this->crud->getUpdateFields());

        // $this->crud->setHeading('Add Book Stock');
        // $this->crud->setSubHeading('adding stock to');

        // // prepare the fields you need to show
        // $this->data['crud'] = $this->crud;
        // $this->data['title'] = 'Add Book Stock '.$this->crud->entity_name;



        // $this->data['saveAction'] = $this->crud->getSaveAction();
        // $this->data['id'] = $id;

        // // Reset the route in form
        // // $this->data['crud']->route .= '/add-book-stock';
        
        // // load the view
        // return view(CRUD::getEditView(), $this->data);





        $this->crud->hasAccessOrFail('addBookStock');

        $this->crud->setHeading('Add Book Stock');
        $this->crud->setSubHeading('adding stock');

        $this->data['crud'] = $this->crud;

        //  Reset the route in form
        $this->data['crud']->route .= '/add-book-stock';

        $this->data['entry'] = $this->crud->getCurrentEntry();
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = "Add book stock ";

        return view('crud::book_stock', $this->data);

    }

    public function PostaddBookStock(){
        CRUD::hasAccessOrFail('addBookStock');
        $request = CRUD::validateRequest();

        // dd($request->book_stock);

        $entry = $this->crud->getCurrentEntry();
        try {
            $GetActiveSchoolYear = SchoolYear::where('is_Active', 1)->first();
            $GetActiveSemester = Semester::where('is_Active', 1)->first();
            $entry->book_stock += $request->book_stock;
            
            $saveHistory = BookHistory::create([
                'school_year_id' => $GetActiveSchoolYear->id,
                'semester_id' => $GetActiveSemester->id,
                'book_id' => $entry->id,
                'qty' => $request->book_stock,
                'description' => $request->book_stock_description,
                'operation' => 'Addition',
                'book_stock_added_at' => $request->book_stock_added_at,
            ]);

            if($entry->save() && $saveHistory){
                Alert::success('Stock Added')->flash();
    
                return redirect(url($this->crud->route));
            }

        } catch (Exception $e) {
            // show a bubble with the error message
            Alert::error("Error, " . $e->getMessage())->flash();

            return redirect()->back()->withInput();
        }
    }
}