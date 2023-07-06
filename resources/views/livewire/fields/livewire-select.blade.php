<div>
    {{-- Do your work, then step back. --}}
    <div class="form-group position-relative">
        <input type="hidden" name="{{ $field['name'] }}" wire:model="field_value">
        <input type="text"
            wire:model.debounce.500ms="form_search"
            placeholder="Search {{$field['label']}}"
            @include('crud::fields.inc.attributes') 
        >
        @if(!empty($form_search) && !empty($search_result))
            <ul class="list-group position-absolute" style="z-index:999; width:100%; box-shadow:0 1px 2px 0 rgba(0, 0, 0, 0.05)">
                @if(!empty($search_result))
                    @foreach($search_result as $item)
                        <li class="list-group-item"style="cursor:pointer" wire:click="setValue({{$item->id}},'{{ $item->{$field["attribute"]} }}')">{{ $item->{$field['attribute']} }}</li>
                    @endforeach
                @endif
                <li class="list-group-item disabled">Masukkan lebih banyak kata kunci untuk filter yang lebih cocok</li>
            </ul>
        @endif
        @if(!empty($field['show_image']) && !empty($form_image))
            <a class="btn btn-primary collapsed btn-small mt-2" data-toggle="collapse" href="#showImage" aria-expanded="false" aria-controls="showImage">Lihat Gambar</a>
            <div class="collapse" id="showImage">
                <div class="card card-body">
                    <img src="{{ asset('storage/'.$form_image) }}" alt="" class="img-thumbnail">
                </div>
            </div>
            
        @endif
    </div>
</div>
