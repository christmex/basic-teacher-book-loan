<?php

namespace App\Http\Controllers\Admin\Operations;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Route;

trait ToggleIsActiveOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupToggleIsActiveRoutes($segment, $routeName, $controller){

        Route::post($segment.'/{id}/toggle-is-active', [
            'as'        => $routeName.'.toggleIsActive',
            'uses'      => $controller.'@toggleIsActive',
            'operation' => 'toggleIsActive',
        ]);
        
    }

    protected function setupToggleIsActiveDefaults() {
        $this->crud->allowAccess('toggleIsActive');
      
        $this->crud->operation(['list', 'show'], function () {
          $this->crud->addButtonFromView('line', 'toggle_is_active', 'toggle_is_active', 'end');
        });
    }

    public function toggleIsActive($id) 
    {
        $this->crud->hasAccessOrFail('toggleIsActive');
        $this->crud->setOperation('toggleIsActive');

        // set is_active to 0 for all row, except the current record
        $this->crud->model->where('id', '!=', $id)->update(['is_active' => 0]);

        // set is _active to 1 for the current record
        $toggleIsActive = $this->crud->model->findOrFail($id)->update(['is_active' => 1]);
        
        return (string) $toggleIsActive;
        
    }
}