@if ($crud->hasAccess('removeBookStock'))
  <a href="{{ url($crud->route.'/'.$entry->getKey().'/remove-book-stock') }}" class="btn btn-sm btn-link text-capitalize"><i class="la la-minus"></i> Remove Stock</a>
@endif