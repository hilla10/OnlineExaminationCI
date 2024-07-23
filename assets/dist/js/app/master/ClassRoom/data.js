var save_label;
var table;

$(document).ready(function () {
  ajaxcsrf();

  table = $('#class').DataTable({
    initComplete: function () {
      var api = this.api();
      $('#class_filter input')
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
      url: base_url + 'ClassRoom/data',
      type: 'POST',
      //data: csrf
    },
    columns: [
      {
        data: 'class_id',
        orderable: false,
        searchable: false,
      },
      { data: 'class_name' },
      { data: 'department_name' },
      {
        data: 'bulk_select',
        orderable: false,
        searchable: false,
      },
    ],
    order: [[1, 'asc']],
    rowId: function (a) {
      return a;
    },
    rowCallback: function (row, data, iDisplayIndex) {
      var info = this.fnPagingInfo();
      var page = info.iPage;
      var length = info.iLength;
      var index = page * length + (iDisplayIndex + 1);
      $('td:eq(0)', row).html(index);
    },
  });

  table.buttons().container().appendTo('#class_wrapper .col-md-6:eq(0)');

  $('#myModal').on('shown.modal.bs', function () {
    $(':input[name="rate"]').select();
  });

  $('#select_all').on('click', function () {
    if (this.checked) {
      $('.check').each(function () {
        this.checked = true;
      });
    } else {
      $('.check').each(function () {
        this.checked = false;
      });
    }
  });

  $('#class tbody').on('click', 'tr .check', function () {
    var check = $('#class tbody tr .check').length;
    var checked = $('#class tbody tr .check:checked').length;
    if (check === checked) {
      $('#select_all').prop('checked', true);
    } else {
      $('#select_all').prop('checked', false);
    }
  });

  $('#bulk').on('submit', function (e) {
    if ($(this).attr('action') == base_url + 'ClassRoom/delete') {
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

function loadDepartment() {
  var department = $('select[name="department_name"]');
  department.children('option:not(:first)').remove();

  ajaxcsrf(); // get csrf token
  $.ajax({
    url: base_url + 'department/loadDepartment',
    type: 'GET',
    success: function (data) {
      //console.log(data);
      if (data.length) {
        var dataDepartment;
        $.each(data, function (key, val) {
          dataDepartment = `<option value="${val.department_id}">${val.department_name}</option>`;
          department.append(dataDepartment);
        });
      }
    },
  });
}

function bulk_delete() {
  if ($('#class tbody tr .check:checked').length == 0) {
    Swal({
      title: 'Failed',
      text: 'No data selected',
      type: 'error',
    });
  } else {
    $('#bulk').attr('action', base_url + 'ClassRoom/delete');
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

function bulk_edit() {
  if ($('#class tbody tr .check:checked').length == 0) {
    Swal({
      title: 'Failed',
      text: 'No data selected',
      type: 'error',
    });
  } else {
    $('#bulk').attr('action', base_url + 'ClassRoom/edit');
    $('#bulk').submit();
  }
}
