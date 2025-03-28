let table;

$(document).ready(function () {
  ajaxcsrf();

  table = $('#exam').DataTable({
    initComplete: function () {
      let api = this.api();
      $('#exam_filter input')
        .off('.DT')
        .on('keyup.DT', function (e) {
          api.search(this.value).draw();
        });
    },
    oLanguage: {
      sProcessing: 'loading...',
    },
    processing: true,
    serverSide: true,
    ajax: {
      url: base_url + 'exam/json',
      type: 'POST',
    },
    columns: [
      {
        data: 'exam_id',
        orderable: false,
        searchable: false,
      },
      {
        data: 'exam_id',
        orderable: false,
        searchable: false,
      },
      { data: 'exam_name' },
      { data: 'course_name' },
      { data: 'total_questions' },
      { data: 'duration' },
      { data: 'type' },
      {
        data: 'token',
        orderable: false,
      },
    ],
    columnDefs: [
      {
        targets: 0,
        data: 'exam_id',
        render: function (data, type, row, meta) {
          return `<div class="text-center">
                    <input name="checked[]" class="check" value="${data}" type="checkbox">
                  </div>`;
        },
      },
      {
        targets: 7,
        data: 'token',
        render: function (data, type, row, meta) {
          return `<div class="text-center">
                    <strong class="badge bg-purple">${data}</strong>
                  </div>`;
        },
      },
      {
        targets: 8,
        data: 'exam_id',
        render: function (data, type, row, meta) {
          return `<div class="text-center">
                    <button type="button" data-id="${data}" class="btn btn-token btn-xs bg-purple">
                      <i class="fa fa-refresh"></i>
                    </button>
                    <a href="${base_url}exam/edit/${data}" class="btn btn-xs btn-warning">
                      <i class="fa fa-edit"></i>
                    </a>
                  </div>`;
        },
      },
    ],
    order: [[1, 'desc']],
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

  $('#exam tbody').on('click', 'tr .check', function () {
    let check = $('#exam tbody tr .check').length;
    let checked = $('#exam tbody tr .check:checked').length;
    if (check === checked) {
      $('.select_all').prop('checked', true);
    } else {
      $('.select_all').prop('checked', false);
    }
  });

  $('#exam').on('click', '.btn-token', function () {
    let id = $(this).data('id');
    let btn = $(this); // Store the button reference

    btn.attr('disabled', 'disabled').children().addClass('fa-spin');
    $.ajax({
      url: base_url + 'exam/refresh_token/' + id,
      type: 'get',
      dataType: 'json',
      success: function (data) {
        if (data.status) {
          btn.removeAttr('disabled'); // Use the stored reference
          btn.children().removeClass('fa-spin'); // Remove spin class
          reload_ajax();
        }
      },
    });
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
            title: 'Success',
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

  table.ajax.url(base_url + 'exam/json/' + lecturer_id).load();
});

function bulk_delete() {
  if ($('#exam tbody tr .check:checked').length == 0) {
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
