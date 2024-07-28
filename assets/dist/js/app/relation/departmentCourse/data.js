let save_label;
let table;

$(document).ready(function () {
  ajaxcsrf();

  table = $('#departmentCourse').DataTable({
    initComplete: function () {
      let api = this.api();
      $('#departmentCourse_filter input')
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
        exportOptions: { columns: [1, 2] },
      },
      {
        extend: 'print',
        exportOptions: { columns: [1, 2] },
      },
      {
        extend: 'excel',
        exportOptions: { columns: [1, 2] },
      },
      {
        extend: 'pdf',
        exportOptions: { columns: [1, 2] },
      },
    ],
    oLanguage: {
      sProcessing: 'loading...',
    },
    processing: true,
    serverSide: true,
    ajax: {
      url: base_url + 'departmentCourse/data',
      type: 'POST',
    },
    columns: [
      {
        data: 'id',
        orderable: false,
        searchable: false,
      },
      { data: 'course_name' },
    ],
    columnDefs: [
      {
        targets: 2,
        searchable: false,
        orderable: false,
        title: 'Department',
        data: 'department_name',
        render: function (data, type, row, meta) {
          let course = data.split(',');
          let badge = [];
          $.each(course, function (i, val) {
            let newcourse = `<span class="badge bg-green">${val}</span>`;
            badge.push(newcourse);
          });
          return badge.join(' ');
        },
      },
      {
        targets: 3,
        searchable: false,
        orderable: false,
        data: 'course_id',
        render: function (data, type, row, meta) {
          return `<div class="text-center">
									<a href="${base_url}departmentCourse/edit/${data}" class="btn btn-warning btn-xs">
										<i class="fa fa-pencil"></i>
									</a>
								</div>`;
        },
      },
      {
        targets: 4,
        data: 'course_id',
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
    .appendTo('#departmentCourse_wrapper .col-md-6:eq(0)');

  $('#myModal').on('shown.modal.bs', function () {
    $(':input[name="rate"]').select();
  });

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

  $('#departmentCourse tbody').on('click', 'tr .check', function () {
    let check = $('#departmentCourse tbody tr .check').length;
    let checked = $('#departmentCourse tbody tr .check:checked').length;
    if (check === checked) {
      $('.select_all').prop('checked', true);
    } else {
      $('.select_all').prop('checked', false);
    }
  });

  $('#bulk').on('submit', function (e) {
    if ($(this).attr('action') == base_url + 'departmentCourse/delete') {
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
  if ($('#departmentCourse tbody tr .check:checked').length == 0) {
    Swal({
      title: 'Failed',
      text: 'No data selected',
      type: 'error',
    });
  } else {
    $('#bulk').attr('action', base_url + 'departmentCourse/delete');
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
