<?php

namespace App\Http\Controllers\Admin\Operations;

use App\Models\Semester;
use App\Models\SchoolYear;
use App\Models\BookHistory;
use Prologue\Alerts\Facades\Alert;
use Illuminate\Support\Facades\Route;
use App\Http\Requests\BookStockRequest;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

trait RemoveBookStockOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupRemoveBookStockRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/{id}/remove-book-stock', [
            'as'        => $routeName.'.removeBookStock',
            'uses'      => $controller.'@removeBookStock',
            'operation' => 'removeBookStock',
        ]);

        // if i remove this that will show error, because i put 
        Route::get($segment.'/remove-book-stock', function(){
            return redirect()->route('book.index');
        })->name($routeName.'.redirect');

        Route::post($segment.'/remove-book-stock/{id}', [
            'as'        => $routeName.'.removeBookStock',
            'uses'      => $controller.'@PostremoveBookStock',
            'operation' => 'removeBookStock',
        ]);

    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupRemoveBookStockDefaults()
    {
        CRUD::allowAccess('removeBookStock');

        CRUD::operation('removeBookStock', function () {
            CRUD::loadDefaultOperationSettingsFromConfig();
            $this->crud->enableInlineErrors();
            $this->crud->enableGroupedErrors();
        });

        CRUD::operation('list', function () {
            // CRUD::addButton('top', 'remove_book_stock', 'view', 'crud::buttons.remove_book_stock');
            CRUD::addButton('line', 'remove_book_stock', 'view', 'crud::buttons.remove_book_stock');
        });
    }

    protected function setupRemoveBookStockOperation(){
        CRUD::setValidation(BookStockRequest::class);
        CRUD::field('book_name')->attributes(['readonly' => 'readonly','disabled' => 'disabled'])->value($this->crud->getCurrentEntry()->book_name);

        CRUD::field('previous_book_stock')->attributes(['readonly' => 'readonly','disabled' => 'disabled'])->value($this->crud->getCurrentEntry()->book_stock);

        CRUD::field('book_stock')->attributes(['min' => 1])->type('number')->default(1);

        CRUD::field('book_stock_description')->type('textarea')->attributes([
            'placeholder' => 'Ex: Kembalikan buku '
        ]);
        CRUD::field('book_stock_added_at')->label('Book stock remove at')->value(now())->type('date');

        CRUD::addSaveAction([
            'name' => 'removeBookStock',
            'redirect' => function ($crud, $request, $itemId) {
                return $crud->route;
            },
            'button_text' => 'Remove Book Stock',
        ]);
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function removeBookStock()
    {
        CRUD::hasAccessOrFail('removeBookStock');

        $this->crud->setHeading('Remove Book Stock');
        $this->crud->setSubHeading('removing stock');

        $this->data['crud'] = $this->crud;
        //  Reset the route in form
        $this->data['crud']->route .= '/remove-book-stock';
        
        $this->data['entry'] = $this->crud->getCurrentEntry();
        $this->data['saveAction'] = $this->crud->getSaveAction();
        $this->data['title'] = "Remove book stock ";

        return view('crud::book_stock', $this->data);
    }

    public function PostRemoveBookStock(){
        CRUD::hasAccessOrFail('removeBookStock');
        $request = CRUD::validateRequest();
        $entry = $this->crud->getCurrentEntry();
        if($request->book_stock > $entry->book_stock){
            
            Alert::error("Error, jumlah buku yang dikurangi melebihi stock yang ada")->flash();

            return redirect()->back()->withInput();
        }
        try {
            $GetActiveSchoolYear = SchoolYear::where('is_Active', 1)->first();
            $GetActiveSemester = Semester::where('is_Active', 1)->first();
            $entry->book_stock -= $request->book_stock;
            
            $saveHistory = BookHistory::create([
                'school_year_id' => $GetActiveSchoolYear->id,
                'semester_id' => $GetActiveSemester->id,
                'book_id' => $entry->id,
                'qty' => $request->book_stock,
                'description' => $request->book_stock_description,
                'operation' => 'Reduction',
                'book_stock_added_at' => $request->book_stock_added_at,
            ]);

            if($entry->save() && $saveHistory){
                Alert::success('Stock Removed')->flash();
    
                return redirect(url($this->crud->route));
            }

        } catch (Exception $e) {
            // show a bubble with the error message
            Alert::error("Error, " . $e->getMessage())->flash();

            return redirect()->back()->withInput();
        }
    }
}