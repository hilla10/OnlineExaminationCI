let table;

$(document).ready(function () {
  ajaxcsrf();

  table = $('#question').DataTable({
    initComplete: function () {
      let api = this.api();
      $('#question_filter input')
        .off('.DT')
        .on('keyup.DT', function (e) {
          api.search(this.value).draw();
        });
    },
    dom:
      "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
      "<'row'<'col-sm-12'tr>>" +
      "<'row'<'col-sm-5'i><'col-sm-7'p>>",
    buttons: [
      {
        extend: 'copy',
        exportOptions: { columns: [2, 3, 4, 5] },
      },
      {
        extend: 'print',
        exportOptions: { columns: [2, 3, 4, 5] },
      },
      {
        extend: 'excel',
        exportOptions: { columns: [2, 3, 4, 5] },
      },
      {
        extend: 'pdf',
        exportOptions: { columns: [2, 3, 4, 5] },
      },
    ],
    oLanguage: {
      sProcessing: 'loading...',
    },
    processing: true,
    serverSide: true,
    ajax: {
      url: base_url + 'question/data',
      type: 'POST',
    },
    columns: [
      {
        data: 'question_id',
        orderable: false,
        searchable: false,
      },
      {
        data: 'question_id',
        orderable: false,
        searchable: false,
      },
      { data: 'lecturer_name' },
      { data: 'course_name' },
      { data: 'question' },
      { data: 'created_on' },
    ],
    columnDefs: [
      {
        targets: 0,
        data: 'question_id',
        render: function (data, type, row, meta) {
          return `<div class="text-center">
									<input name="checked[]" class="check" value="${data}" type="checkbox">
								</div>`;
        },
      },
      {
        targets: 6,
        data: 'question_id',
        render: function (data, type, row, meta) {
          return `<div class="text-center">
                                <a href="${base_url}question/detail/${data}" class="btn btn-xs btn-success">
                                    <i class="fa fa-eye"></i> Detail
                                </a>
                                <a href="${base_url}question/edit/${data}" class="btn btn-xs btn-warning">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                            </div>`;
        },
      },
    ],
    order: [[5, 'desc']],
    rowId: function (a) {
      return a;
    },
    rowCallback: function (row, data, iDisplayIndex) {
      let info = this.fnPagingInfo();
      let page = info.iPage;
      let length = info.iLength;
      let index = page * length + (iDisplayIndex + 1);
      $('td:eq(1)', row).html(index);
    },
  });

  table.buttons().container().appendTo('#question_wrapper .col-md-6:eq(0)');

  $('.select_all').on('click', function () {
    if (this.checked) {
      $('.check').each(function () {
        this.checked = true;
        $('.select_all').prop('checked', true);
      });
    } else {
      $('.check').each(function () {
        this.checked = false;
        $('.select_all').prop('checked', false);
      });
    }
  });

  $('#question tbody').on('click', 'tr .check', function () {
    let check = $('#question tbody tr .check').length;
    let checked = $('#question tbody tr .check:checked').length;
    if (check === checked) {
      $('.select_all').prop('checked', true);
    } else {
      $('.select_all').prop('checked', false);
    }
  });

  $('#bulk').on('submit', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();

    $.ajax({
      url: $(this).attr('action'),
      data: $(this).serialize(),
      type: 'POST',
      success: function (respon) {
        if (respon.status) {
          Swal({
            title: 'Successful',
            text: respon.total + ' data deleted successfully',
            type: 'success',
          });
        } else {
          Swal({
            title: 'Failed',
            text: 'No data selected',
            type: 'error',
          });
        }
        reload_ajax();
      },
      error: function () {
        Swal({
          title: 'Failed',
          text: 'There is data in use',
          type: 'error',
        });
      },
    });
  });
});

function bulk_delete() {
  if ($('#question tbody tr .check:checked').length == 0) {
    Swal({
      title: 'Failed',
      text: 'No data selected',
      type: 'error',
    });
  } else {
    Swal({
      title: 'You sure?',
      text: 'Data will be deleted!',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Delete!',
    }).then((result) => {
      if (result.value) {
        $('#bulk').submit();
      }
    });
  }
}
