@if ($crud->hasAccess('bulkReturnBook') && $crud->get('list.bulkActions'))
  <a href="javascript:void(0)" onclick="bulkReturnBookEntries(this)" class="btn btn-sm btn-secondary bulk-button"><i class="la la-sync"></i> Return Book</a>
@endif

@push('after_scripts')
<script>
  if (typeof bulkReturnBookEntries != 'function') {
    function bulkReturnBookEntries(button) {

        if (typeof crud.checkedItems === 'undefined' || crud.checkedItems.length == 0)
        {
            new Noty({
            type: "warning",
            text: "<strong>{{ trans('backpack::crud.bulk_no_entries_selected_title') }}</strong><br>{{ trans('backpack::crud.bulk_no_entries_selected_message') }}"
          }).show();

          return;
        }

        var message = "Are you sure you want to return this :number books entries?";
        message = message.replace(":number", crud.checkedItems.length);

        // show confirm message
        swal({
        title: "{{ trans('backpack::base.warning') }}",
        text: message,
        icon: "warning",
        buttons: {
          cancel: {
          text: "{{ trans('backpack::crud.cancel') }}",
          value: null,
          visible: true,
          className: "bg-secondary",
          closeModal: true,
        },
          delete: {
          text: "Return Book",
          value: true,
          visible: true,
          className: "bg-primary",
        }
        },
      }).then((value) => {
        if (value) {
          var ajax_calls = [];
              var bulk_return_book_route = "{{ url($crud->route) }}/bulk-return-book";

          // submit an AJAX delete call
          $.ajax({
            url: bulk_return_book_route,
            type: 'POST',
            data: { entries: crud.checkedItems },
            success: function(result) {
              // Show an alert with the result
                    new Noty({
                    type: "success",
                    text: "<strong>Returned successfully</strong><br>entries have been returned."
                  }).show();

              crud.checkedItems = [];
              crud.table.ajax.reload();
            },
            error: function(result) {
              // Show an alert with the result
                    new Noty({
                    type: "danger",
                    text: "<strong>Return failed</strong><br>"+JSON.parse(result.responseText).error
                  }).show();
            }
          });
        }
      });
      }
  }
</script>
@endpush