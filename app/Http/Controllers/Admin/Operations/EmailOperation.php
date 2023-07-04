<?php

namespace App\Http\Controllers\Admin\Operations;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Route;

trait EmailOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupEmailRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/email', [
            'as'        => $routeName.'.email',
            'uses'      => $controller.'@email',
            'operation' => 'email',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupEmailDefaults()
    {
        CRUD::allowAccess('email');

        CRUD::operation('email', function () {
            CRUD::loadDefaultOperationSettingsFromConfig();
        });

        CRUD::operation('list', function () {
            // CRUD::addButton('top', 'email', 'view', 'crud::buttons.email');
            // CRUD::addButton('line', 'email', 'view', 'crud::buttons.email');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function email()
    {
        CRUD::hasAccessOrFail('email');

        // prepare the fields you need to show
        $this->data['crud'] = $this->crud;
        $this->data['title'] = CRUD::getTitle() ?? 'Email '.$this->crud->entity_name;

        // load the view
        return view('crud::operations.email', $this->data);
    }
}