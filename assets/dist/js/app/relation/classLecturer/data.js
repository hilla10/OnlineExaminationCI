let save_label;
let table;

$(document).ready(function () {
  ajaxcsrf();

  table = $('#classLecturer').DataTable({
    initComplete: function () {
      let api = this.api();
      $('#ClassLecturer_filter input')
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
        exportOptions: { columns: [1, 2, 3] },
      },
      {
        extend: 'print',
        exportOptions: { columns: [1, 2, 3] },
      },
      {
        extend: 'excel',
        exportOptions: { columns: [1, 2, 3] },
      },
      {
        extend: 'pdf',
        exportOptions: { columns: [1, 2, 3] },
      },
    ],
    oLanguage: {
      sProcessing: 'loading...',
    },
    processing: true,
    serverSide: true,
    ajax: {
      url: base_url + 'classLecturer/data',
      type: 'POST',
    },
    columns: [
      {
        data: 'id',
        orderable: false,
        searchable: false,
      },
      { data: 'teacher_id' },
      { data: 'lecturer_name' },
    ],
    columnDefs: [
      {
        targets: 3,
        searchable: false,
        orderable: false,
        title: 'ClassRoom',
        data: 'class',
        render: function (data, type, row, meta) {
          let classList = data.split(',');
          let badge = [];
          $.each(classList, function (i, val) {
            let newclass = `<span class="badge bg-green">${val}</span>`;
            badge.push(newclass);
          });
          return badge.join(' ');
        },
      },
      {
        targets: 4,
        searchable: false,
        orderable: false,
        data: 'lecturer_id',
        render: function (data, type, row, meta) {
          return `<div class="text-center">
									<a href="${base_url}classLecturer/edit/${data}" class="btn btn-warning btn-xs">
										<i class="fa fa-pencil"></i>
									</a>
								</div>`;
        },
      },
      {
        targets: 5,
        searchable: false,
        orderable: false,
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

  table
    .buttons()
    .container()
    .appendTo('#ClassLecturer_wrapper .col-md-6:eq(0)');

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

  $('#classLecturer tbody').on('click', 'tr .check', function () {
    let check = $('#classLecturer tbody tr .check').length;
    let checked = $('#classLecturer tbody tr .check:checked').length;
    if (check === checked) {
      $('.select_all').prop('checked', true);
    } else {
      $('.select_all').prop('checked', false);
    }
  });

  $('#bulk').on('submit', function (e) {
    if ($(this).attr('action') == base_url + 'classLecturer/delete') {
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
    }
  });
});

function bulk_delete() {
  if ($('#classLecturer tbody tr .check:checked').length == 0) {
    Swal({
      title: 'Failed',
      text: 'No data selected',
      type: 'error',
    });
  } else {
    $('#bulk').attr('action', base_url + 'classLecturer/delete');
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
