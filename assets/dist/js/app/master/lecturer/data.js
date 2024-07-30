let table;

$(document).ready(function () {
  ajaxcsrf();

  table = $('#lecturer').DataTable({
    initComplete: function () {
      let api = this.api();
      $('#lecturer_filter input')
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
        exportOptions: { columns: [1, 2, 3, 4] },
      },
      {
        extend: 'print',
        exportOptions: { columns: [1, 2, 3, 4] },
      },
      {
        extend: 'excel',
        exportOptions: { columns: [1, 2, 3, 4] },
      },
      {
        extend: 'pdf',
        exportOptions: { columns: [1, 2, 3, 4] },
      },
    ],
    oLanguage: {
      sProcessing: 'loading...',
    },
    processing: true,
    serverSide: true,
    ajax: {
      url: base_url + 'lecturer/data',
      type: 'POST',
    },
    columns: [
      {
        data: 'lecturer_id',
        orderable: false,
        searchable: false,
      },
      { data: 'teacher_id' },
      { data: 'lecturer_name' },
      { data: 'email' },
      { data: 'course_name' },
    ],
    columnDefs: [
      {
        searchable: false,
        targets: 5,
        data: {
          lecturer_id: 'lecturer_id',
          ada: 'ada',
        },
        render: function (data, type, row, meta) {
          let btn;
          if (data.ada > 0) {
            btn = '';
          } else {
            btn = `<button type="button" class="btn btn-active btn-primary btn-xs" data-id="${data.lecturer_id}">
								<i class="fa fa-user-plus"></i> Active
							</button>`;
          }
          return `<div class="text-center">
							<a href="${base_url}lecturer/edit/${data.lecturer_id}" class="btn btn-xs btn-warning">
								<i class="fa fa-pencil"></i> Edit
							</a>
							${btn}
						</div>`;
        },
      },
      {
        targets: 6,
        data: 'lecturer_id',
        render: function (data, type, row, meta) {
          return `<div class="text-center">
									<input name="checked[]" class="check" value="${data}" type="checkbox">
								</div>`;
        },
      },
    ],
    order: [[1, 'asc']],
    rowId: function (a) {
      return a;
    },
    rowCallback: function (row, data, iDisplayIndex) {
      let info = this.fnPagingInfo();
      let page = info.iPage;
      let length = info.iLength;
      let index = page * length + (iDisplayIndex + 1);
      $('td:eq(0)', row).html(index);
    },
  });

  table.buttons().container().appendTo('#lecturer_wrapper .col-md-6:eq(0)');

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

  $('#lecturer tbody').on('click', 'tr .check', function () {
    let check = $('#lecturer tbody tr .check').length;
    let checked = $('#lecturer tbody tr .check:checked').length;
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

  $('#lecturer').on('click', '.btn-active', function () {
    let id = $(this).data('id');

    $.ajax({
      url: base_url + 'lecturer/create_user',
      data: 'id=' + id,
      type: 'GET',
      success: function (response) {
        if (response.msg) {
          let title = response.status ? 'Successful' : 'Failed';
          let type = response.status ? 'success' : 'error';
          Swal({
            title: title,
            text: response.msg,
            type: type,
          });
        }
        reload_ajax();
      },
    });
  });
});

function bulk_delete() {
  if ($('#lecturer tbody tr .check:checked').length == 0) {
    Swal({
      title: 'Failed',
      text: 'No data selected',
      type: 'error',
    });
  } else {
    Swal({
      title: 'Are you sure?',
      text: 'Data will be deleted!',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Wipe It!',
    }).then((result) => {
      if (result.value) {
        $('#bulk').submit();
      }
    });
  }
}
