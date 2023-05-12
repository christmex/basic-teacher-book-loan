@if ($crud->hasAccess('toggleIsActive') && $entry->is_active != 1)
    <a href="javascript:void(0)" onclick="toggleIsActive(this)" data-route="{{ url($crud->route.'/'.$entry->getKey().'/toggle-is-active') }}" class="btn btn-sm btn-link text-capitalize" data-button-type="toggleIsActive"><i class="la la-toggle-off"></i> Activate</a>
@endif

{{-- Button Javascript --}}
{{-- - used right away in AJAX operations (ex: List) --}}
{{-- - pushed to the end of the page, after jQuery is loaded, for non-AJAX operations (ex: Show) --}}
@push('after_scripts') @if (request()->ajax()) @endpush @endif
<script>
    if (typeof toggleIsActive != 'function') {
      $("[data-button-type=clone]").unbind('click');

      function toggleIsActive(button) {
          // ask for confirmation before deleting an item
          // e.preventDefault();
          var button = $(button);
          var route = button.attr('data-route');

          $.ajax({
              url: route,
              type: 'POST',
              success: function(result) {
                  // Show an alert with the result
                  new Noty({
                    type: "success",
                    text: "<strong>Successfully</strong><br>Activated {{$crud->entity_name}}"
                  }).show();

                  // Hide the modal, if any
                  $('.modal').modal('hide');

                  if (typeof crud !== 'undefined') {
                    crud.table.ajax.reload();
                  }
              },
              error: function(result) {
                  // Show an alert with the result
                  new Noty({
                    type: "error",
                    text: "<strong>Failed</strong><br>"+JSON.parse(result.responseText).error
                  }).show();
              }
          });
      }
    }

    // make it so that the function above is run after each DataTable draw event
    // crud.addFunctionToDataTablesDrawEventQueue('cloneEntry');
</script>
@if (!request()->ajax()) @endpush @endif