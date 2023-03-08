@if ($crud->hasAccess('addBookStock'))
  <a href="{{ url($crud->route.'/'.$entry->getKey().'/add-book-stock') }}" class="btn btn-sm btn-link text-capitalize"><i class="la la-plus"></i> Add Stock</a>
@endif