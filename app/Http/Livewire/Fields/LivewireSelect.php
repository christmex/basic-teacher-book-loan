<?php

namespace App\Http\Livewire\Fields;

use App\Models\Book;
use Livewire\Component;

class LivewireSelect extends Component
{
    public $field;
    public $field_value;
    public $form_search;
    public $search_result;
    public $modelDefined;

    public function mount(){
        // $this->modelDefined = $this->field['model'];
        
        if($this->field['value']){
            $this->field_value = $this->field['value'];
        }
        if(old($this->field['name'])){
            $this->field_value = old($this->field['name']);
        }
        if($this->field['value'] || old($this->field['name'])){
            $this->form_search = $this->field['model']::where('id',$this->field_value)->first()->{$this->field['attribute']};
        }
    }

    public function render()
    {
        return view('livewire.fields.livewire-select');
    }

    public function updatedFormSearch(){
        if(!empty($this->form_search)){
            if($this->field['is_book']){
                $this->search_result = $this->field['model']::where($this->field['attribute'],'LIKE','%'.$this->form_search.'%')->where('book_stock','>=', 1)->paginate(5)->items();
            }else {
                $this->search_result = $this->field['model']::where($this->field['attribute'],'LIKE','%'.$this->form_search.'%')->paginate(5)->items();
            }
            if(count($this->search_result) == 0){
                $this->field_value = NULL;
            }
        }else {
            $this->field_value = NULL;
        }
    }

    public function setValue($id,$value){
        $this->search_result = NULL;
        $this->field_value = $id;
        $this->form_search = $value;
    }
}
